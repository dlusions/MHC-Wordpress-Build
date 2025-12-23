<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Instagram;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Instagram\InstagramApiInterface;

class InstagramAuthDriverController
{
    /**
     * @var string
     */
    public const PRE_SAVE_ENDPOINT = 'yooessentials/auth/instagram';

    public function presave(Request $request, Response $response, AuthManager $authManager, InstagramApiInterface $api)
    {
        $form = $request->getParam('auth', []);

        $driver = $form['driver'] ?? null;
        $scopes = $form['scopes'] ?? [];
        $token = $form['accessToken'] ?? null;

        if (empty($scopes)) {
            $scopes = $request->getParam('requiredScopes', []);
        }

        try {
            if (!$driver) {
                throw new \RuntimeException('Driver not provided.', 400);
            }

            if (!$token) {
                throw new \RuntimeException('Access Token not provided.', 400);
            }

            if (!$scopes) {
                throw new \RuntimeException('Scopes must be specified.', 400);
            }

            /** @var AuthDriver $authDriver */
            $authDriver = $authManager->driver($driver);

            /** @var AuthOAuth $auth */
            $auth = $authManager
                ->initAuth($form)
                ->forDriver($authDriver)
                ->setScopes($scopes);

            $api = $api
                ->forDriver($driver)
                ->withAccessToken($auth->accessToken);

            $form['userId'] = $form['id'] ?? null;

            if ($driver === 'facebook') {
                $tokenInfo = $api->debugToken($token);
                $form['userId'] = $tokenInfo['id'] ?? $form['id'] ?? null;
            } else {
                $tokenInfo = $api->me();
                $form['userId'] = $tokenInfo['user_id'] ?? $tokenInfo['id'] ?? $form['id'] ?? null;
            }
        } catch (\Throwable $e) {
            return $response->withJson('Invalid or expired Access Token, please re-authenticate.', 400);
        }

        return $response->withJson($form, 200);
    }
}
