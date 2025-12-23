<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Database;

use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use ZOOlanders\YOOessentials\Form\Action\SaveToAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class DatabaseUpsertAction extends SaveToAction
{
    use HasDatabase, PreparesDbFilters;

    public const NAME = 'database-record-upsert';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();

        $db = $this->db((array) $config);
        $data = $this->resolveData($config->content ?? []);

        $this->updateOrInsert($config, $db, $data);

        return $next(
            $response->withDataLog([
                self::NAME => [
                    'config' => $config,
                    'data' => $data,
                ],
            ])
        );
    }

    private function updateOrInsert(object $config, Database $db, array $data)
    {
        $shouldUpdate = $config->update_if_exists ?? false;

        if (!$shouldUpdate) {
            $this->insertRecord($db, $config, $data);

            return;
        }

        if (empty($config->table_record ?? [])) {
            throw ActionConfigurationException::create($this, 'Could not update data, incomplete configuration.');
        }

        try {
            $count = $this->countRecords($config, $db);
        } catch (\Throwable $exception) {
            throw ActionRuntimeException::create($this, 'Query Error: ' . $exception->getMessage());
        }

        if ($count > 1 && !($config->match_multiple ?? false)) {
            throw ActionRuntimeException::create($this, 'Could not update data, more than one match found.');
        }

        if ($count === 0) {
            $this->insertRecord($db, $config, $data);

            return;
        }

        $result = $db->update($config->table, $data, $this->getFiltersFromConfig($config));

        if ($result === false) {
            throw ActionRuntimeException::create($this, 'Could not insert or update data.');
        }
    }

    private function insertRecord(Database $db, object $config, array $data): void
    {
        $result = $db->insert($config->table, $data);

        if (!$result) {
            throw ActionRuntimeException::create($this, 'Could not insert data.');
        }
    }

    protected function resolveData(array $content): array
    {
        $content = self::removeDisabledValues($content);
        $content = self::setNullValues($content);
        $content = self::removeEmptyValues($content);

        return parent::resolveData($content);
    }

    private static function setNullValues(array $content): array
    {
        return array_map(function ($row) {
            $row = (array) $row;
            $null = $row['props']['set_null'] ?? false;
            if ($null) {
                $row['props']['value'] = null;
            }

            return $row;
        }, $content);
    }

    private static function removeEmptyValues(array $content): array
    {
        return array_filter($content, function ($row) {
            $row = (array) $row;
            $value = $row['props']['value'] ?? null;
            $skip_empty = $row['props']['skip_if_empty'] ?? false;

            if ($skip_empty && $value === '') {
                return false;
            }

            return true;
        });
    }

    private static function removeDisabledValues(array $content): array
    {
        return array_filter($content, function ($row) {
            $row = (array) $row;
            $disabled = $row['props']['status'] ?? '' === 'disabled';

            if ($disabled) {
                return false;
            }

            return true;
        });
    }
}
