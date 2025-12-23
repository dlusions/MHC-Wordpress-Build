<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth;

use YOOtheme\Config;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class AuthController
{
    public const GENERATE_ID_ENDPOINT = 'yooessentials/auth/generate-id';

    public function generateId(Request $request, Response $response, Config $config)
    {
        $prefix = $request->getParam('driver', '');

        return $response->withJson([
            'id' => md5(uniqid($prefix . '-') . $config->get('app.secret')),
        ]);
    }
}
