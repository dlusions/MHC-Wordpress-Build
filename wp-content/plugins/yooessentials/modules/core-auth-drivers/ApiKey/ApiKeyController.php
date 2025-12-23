<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\ApiKey;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class ApiKeyController
{
    /**
     * @var string
     */
    public const PRESAVE_ENDPOINT = 'yooessentials/auth/api-key';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('auth', []);
        $key = $form['key'] ?? null;

        if (!$key) {
            return $response->withJson('The API Key cannot be omitted.', 400);
        }

        return $response->withJson(200);
    }
}
