<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class StorageAdapterController
{
    public const PRESAVE_ENDPOINT = 'yooessentials/storage/adapter/presave';

    public static function presave(Request $request, Response $response, StorageAdapterManager $manager)
    {
        $form = $request->getParam('storage') ?? $request->getParam('form');
        $adapter = $form['adapter'] ?? null;

        if (!$adapter) {
            return $response->withJson("Adapter Not Found: $adapter.", 400);
        }

        try {
            $manager->adapter($adapter)->validateConfig($form);
        } catch (StorageConfigurationInvalidException $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(200);
    }
}
