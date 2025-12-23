<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Google;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class GoogleAuthDriverController
{
    public function presaveKey(Request $request, Response $response)
    {
        $form = $request->getParam('auth', []);
        $key = $form['key'] ?? null;

        if (!$key) {
            return $response->withJson('API Key is required.', 400);
        }

        return $response->withJson(200);
    }
}
