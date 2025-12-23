<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\BasicAuth;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class BasicAuthController
{
    /**
     * @var string
     */
    public const PRESAVE_ENDPOINT = 'yooessentials/auth/basic-auth';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('auth', []);
        $username = $form['username'] ?? null;
        $password = $form['password'] ?? null;

        if (!$username) {
            return $response->withJson('The username cannot be omitted.', 400);
        }

        if (!$password) {
            return $response->withJson('The password cannot be omitted.', 400);
        }

        return $response->withJson(200);
    }
}
