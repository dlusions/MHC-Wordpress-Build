<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database;

use YOOtheme\Arr;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Source\SourceService;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\DatabaseResolver;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\Relation;

class DatabaseController
{
    use HasDynamicFields;

    /**
     * @var string
     */
    public const TABLES_URL = '/yooessentials/source/database/tables';
    public const FIELDS_URL = '/yooessentials/source/database/fields';
    public const FILTER_FIELDS_URL = '/yooessentials/source/database/filter-fields';
    public const RECORDS_URL = '/yooessentials/source/database/records';
    public const PRESAVE_ENDPOINT = '/yooessentials/source/database';

    public function presave(Request $request, Response $response)
    {
        $config = $request->getParam('form') ?? [];
        $table = $config['table'] ?? false;
        $tableKey = $config['table_primary_key'] ?? false;

        try {
            if (!$table) {
                return $request->abort(400, 'Table must be specified.');
            }

            $source = new DatabaseSource($config);
            $columns = $source->tableColumns();
            $relations = $source->relationsConfig();

            if (!$columns) {
                return $request->abort(400, 'Table must have at least one column.');
            }

            if ($relations && !$tableKey) {
                return $request->abort(400, 'Table Primary Key must be specified.');
            }

            // Check relations config
            foreach ($relations as $relation) {
                new Relation($source, $relation);
            }

            // Check that relations have unique names
            $names = [$source->table()];
            foreach ($source->relations() as $relation) {
                $source->tableColumns($relation->table());
                $names[$relation->tableAlias()] = $relation->name();
            }

            // this is <= because we count also the main table
            if (count($names) <= count($source->relations())) {
                return $response->withJson(
                    'You have duplicated names in your relations. Be sure to customize them to be unique.',
                    400
                );
            }
        } catch (\Throwable $e) {
            return $request->abort(400, $e->getMessage());
        }

        return $response->withJson(200);
    }

    public function tables(Request $request, Response $response)
    {
        $config = $request->getParam('form') ?? [];
        $query = $request->getParam('query') ?? null;

        try {
            $source = new DatabaseSource($config);

            $prefix = $source->db()->prefix;
            $tables = $source->db()->fetchAll('SHOW TABLES');

            $items = array_map(function ($table) use ($prefix) {
                $id = array_values($table)[0] ?? null;

                return [
                    'meta' => $id,
                    'value' => $id,
                    'text' => self::labelField(preg_replace("/^$prefix/", '', $id)),
                ];
            }, $tables);

            if ($query) {
                $items = array_values(
                    array_filter($items, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text']))
                );
            }

            return $response->withJson($items, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function fields(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $table = $request->getParam('table') ?? null;
        $query = $request->getParam('query') ?? null;
        $tableFieldPath = $request->getParam('table_field_path') ?? null;

        // if table path provided, resolve it first
        if (!$table && $tableFieldPath) {
            $table = Arr::get($form, $tableFieldPath);
        }

        try {
            if (!$table) {
                throw new \Exception('Table must be specified.');
            }

            $source = new DatabaseSource($form);
            $fields = self::getFieldsForTable($source, $table);

            if ($query) {
                $fields = array_values(
                    array_filter($fields, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text']))
                );
            }

            return $response->withJson($fields, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function filterFields(Request $request, Response $response, SourceService $sourceService)
    {
        $form = $request->getParam('form') ?? [];
        $tableFieldPath = $request->getParam('table_field_path') ?? null;
        $sourceId = $request->getParam('source_id');

        $table = Arr::get($form, $tableFieldPath);

        try {
            if (!$table) {
                throw new \Exception('Table must be specified.');
            }

            if (!$sourceId) {
                throw new \Exception('Error while loading the source configuration');
            }

            /** @var DatabaseSource $source */
            $source = $sourceService->source($sourceId);

            if (!$source) {
                throw new \Exception('Error while loading the source');
            }

            if ($table !== $source->table()) {
                $relation = $source->relationFromTableAlias($table);
                if ($relation) {
                    $table = $relation->table();
                }
            }

            $fields = self::getFieldsForTable($source, $table);

            return $response->withJson($fields, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function records(Request $request, Response $response, SourceService $sourceManager)
    {
        $sourceId = $request->getParam('source_id') ?? [];

        try {
            $source = $sourceManager->source($sourceId);
            $primaryKey = $source->config()['table_primary_key'] ?? '';

            if (!$primaryKey) {
                throw new \Exception('The Primary Key must be set in the Source configuration.');
            }

            $records = (new DatabaseResolver($source))
                ->id($args['id'] ?? null)
                ->offset($args['offset'] ?? 0)
                ->limit($args['limit'] ?? 50)
                ->resolve();

            $records = array_filter($records, fn ($record) => $record[$primaryKey] ?? false);

            $items = array_map(fn ($record) => [
                'id' => $record[$primaryKey],
                'meta' => json_encode($record),
            ], $records);

            return $response->withJson($items, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    private static function getFieldsForTable(DatabaseSource $source, $table): array
    {
        $columns = $source->db()->fetchAll('SHOW FULL COLUMNS FROM ' . ($table ?? ''));

        $fields = array_filter(
            array_map(function ($column) {
                $id = $column['Field'] ?? null;

                return [
                    'meta' => $id,
                    'value' => $id,
                    'text' => self::labelField($id),
                ];
            }, $columns)
        );

        return $fields;
    }
}
