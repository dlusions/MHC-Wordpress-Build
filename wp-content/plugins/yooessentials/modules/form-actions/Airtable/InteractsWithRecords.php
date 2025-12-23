<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Airtable;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Airtable\AirtableApiInterface;
use ZOOlanders\YOOessentials\Form\Action\Action;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;

trait InteractsWithRecords
{
    protected static function api(Action $action): ?AirtableApiInterface
    {
        $config = (object) $action->getConfig();
        $auth = app(AuthManager::class)->auth($config->auth);

        if (!$auth) {
            throw ActionRuntimeException::create($action, 'Invalid Auth');
        }

        return app(AirtableApiInterface::class)->withAccessToken($auth->accessToken);
    }

    private static function createOrUpdateRecord(Action $action, array $data, bool $typecast = false): array
    {
        $config = (object) $action->getConfig();

        $base = $config->base ?? null;
        $table = $config->table ?? null;
        $record = $config->record ?? null;
        $replace = $config->replace ?? false;

        if (!$base) {
            throw ActionRuntimeException::create($action, 'Invalid Base');
        }

        if (!$table) {
            throw ActionRuntimeException::create($action, 'Invalid Table');
        }

        if ($record) {
            try {
                return self::api($action)->updateRecord($base, $table, $record, [
                    'typecast' => $typecast,
                    'fields' => array_filter($data),
                ], $replace);
            } catch (\Throwable $e) {
                if ($e->getCode() === 404) {
                    throw ActionRuntimeException::create($action, 'Record does not exist.');
                }

                throw $e;
            }
        }

        return self::api($action)->createRecords($base, $table, [
            'typecast' => $typecast,
            'fields' => array_filter($data),
        ]);
    }

    private static function deleteRecord(Action $action): array
    {
        $config = (object) $action->getConfig();

        $base = $config->base ?? null;
        $table = $config->table ?? null;
        $record = $config->record ?? null;

        if (!$base) {
            throw ActionRuntimeException::create($action, 'Invalid Base');
        }

        if (!$table) {
            throw ActionRuntimeException::create($action, 'Invalid Table');
        }

        if (!$record) {
            throw ActionRuntimeException::create($action, 'Invalid Record');
        }

        try {
            return self::api($action)->deleteRecord($base, $table, $record);
        } catch (\Throwable $e) {
            if ($e->getCode() === 404) {
                throw ActionRuntimeException::create($action, 'Record does not exist.');
            }

            throw $e;
        }
    }
}
