<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Config;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Migration\MigrationService;

class ConfigController
{
    public const CONFIG_SAVE_URL = 'yooessentials/config/save';
    public const CONFIG_IMPORT_URL = 'yooessentials/config/import';
    public const CONFIG_EXPORT_URL = 'yooessentials/config/export';

    public static function save(Request $request, Response $response, Config $config, ConfigRepositoryInterface $repository)
    {
        try {
            if (!$repository->authorize()) {
                throw new \Exception('Saving Config Failed: Insufficient User Rights', 403);
            }

            $config->replace($request('config', []));
            $repository->save($config);
        } catch (\Throwable $e) {
            $request->abort($e->getCode(), $e->getMessage());
        }

        return $response->withJson(['data' => $config->toArray()], 200);
    }

    public static function import(Request $request, Response $response, MigrationService $migrations)
    {
        $config = $migrations->migrateConfig($request('config', []));

        return $response->withJson($config, 200);
    }

    public static function export(Response $response, Config $config, ConfigRepositoryInterface $configRepository)
    {
        $values = $configRepository->export($config);

        return $response->withJson($values, 200);
    }
}
