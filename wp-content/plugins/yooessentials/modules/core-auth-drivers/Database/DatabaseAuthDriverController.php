<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Database;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Database\DatabaseManager;
use ZOOlanders\YOOessentials\Util\Prop;
use function YOOtheme\app;

class DatabaseAuthDriverController
{
    public const PRESAVE_ENDPOINT = 'yooessentials/auth/database';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('auth', []);
        $options = Prop::filterByPrefix($form, 'db_');
        $options = array_filter($options);

        $options['external'] = true;

        try {
            /** @var Database $db */
            app(DatabaseManager::class)->initialize($options);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(true, 200);
    }
}
