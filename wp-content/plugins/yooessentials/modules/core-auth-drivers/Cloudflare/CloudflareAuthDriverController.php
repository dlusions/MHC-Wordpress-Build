<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Cloudflare;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Cloudflare\CloudflareApi;

class CloudflareAuthDriverController
{
    public const PRESAVE_API_TOKEN_ENDPOINT = 'yooessentials/auth/cloudflare/api-token';

    public function presave(Request $request, Response $response, AuthManager $authManager, CloudflareApi $api)
    {
        $form = $request->getParam('auth') ?? [];

        $driver = $form['driver'] ?? null;
        $token = $form['accessToken'] ?? null;

        try {
            if (!$driver) {
                throw new \RuntimeException('Driver not provided.', 400);
            }

            if (!$token) {
                throw new \RuntimeException('Access Token not provided.', 400);
            }

            /** @var AuthDriver $authDriver */
            $authDriver = $authManager->driver($driver);

            /** @var Auth $auth */
            $auth = $authManager
                ->initAuth($form)
                ->forDriver($authDriver);

            $tokenInfo = $api->verifyToken($auth->accessToken);

            if ($tokenInfo['status'] !== 'active') {
                throw new \RuntimeException('Access Token is not valid.', 400);
            }
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($tokenInfo, 200);
    }
}
