<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Twitter;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class TwitterSourceController
{
    public const PRESAVE_ENDPOINT = 'yooessentials/source/twitter';

    public function presave(Request $request, Response $response, AuthManager $authManager)
    {
        $form = $request->getParam('form') ?? [];
        $account = $form['account'] ?? null;

        if (!$account) {
            return $response->withJson('Account must be specified.', 400);
        }

        if (!$authManager->auth($account)) {
            return $response->withJson('Account is invalid.', 400);
        }

        return $response->withJson(200);
    }
}
