<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Database;

use YOOtheme\Str;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class DatabaseActionController
{
    use HasDatabase;

    public function getTableList(Request $request, Response $response)
    {
        $config = $request->getParam('form') ?? [];
        $query = $request->getParam('query') ?? '';

        try {
            $db = $this->db($config);

            $prefix = $db->prefix;
            $tables = $db->fetchAll('SHOW TABLES');

            $items = array_map(function ($table) use ($prefix) {
                $id = array_values($table)[0] ?? null;

                return [
                    'value' => $id,
                    'text' => Str::titleCase(Str::snakeCase(preg_replace("/^$prefix/", '', $id), ' ')),
                    'meta' => $id,
                ];
            }, $tables);

            if ($query) {
                $items = array_values(array_filter($items, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text'])));
            }

            return $response->withJson($items, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function getTableColumns(Request $request, Response $response)
    {
        $config = $request->getParam('form') ?? [];
        $table = $config['table'] ?? '';

        try {
            if (!$table) {
                throw new \Exception('Table must be specified.');
            }

            $db = $this->db($config);
            $result = $db->fetchAll("SHOW FULL COLUMNS FROM $table");

            $columns = array_reduce(
                $result,
                function ($carry, $col) {
                    if ($col['Extra'] === 'auto_increment') {
                        return $carry;
                    }

                    $carry[] = [
                        'id' => $col['Field'],
                        'meta' => $col['Type'],
                        'title' => Str::titleCase($col['Field']),
                    ];

                    return $carry;
                },
                []
            );
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($columns);
    }

    public function getTableFields(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $parentForm = $request->getParam('parentForm') ?? [];

        $config = array_merge($parentForm, $form);

        try {
            if (!isset($config['table'])) {
                throw new \Exception('Table must be specified.');
            }

            $db = $this->db($config);
            $columns = $db->fetchAll("SHOW FULL COLUMNS FROM {$config['table']}");

            $fields = array_filter(
                array_map(function ($col) {
                    $id = $col['Field'] ?? null;

                    return [
                        'value' => $id,
                        'meta' => $col['Type'],
                        'text' => Str::titleCase(Str::snakeCase($id, ' ')),
                    ];
                }, $columns)
            );

            return $response->withJson($fields, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
