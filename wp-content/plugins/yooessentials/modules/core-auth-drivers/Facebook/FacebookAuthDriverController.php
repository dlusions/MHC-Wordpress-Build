<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Facebook;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Facebook\FacebookApiInterface;

class FacebookAuthDriverController
{
    /**
     * @var string
     */
    public const PRE_SAVE_ENDPOINT = 'yooessentials/auth/facebook';

    const CLIENT_ID = '827190311385289';
    const CLIENT_SECRET = 'a00bc303fa2988fb0b4f417bfaa6e13c';

    public function presave(Request $request, Response $response, AuthManager $authManager, FacebookApiInterface $api)
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

            $tokenInfo = $api->debugToken($auth->accessToken, self::CLIENT_ID, self::CLIENT_SECRET);

            if ($tokenInfo['is_valid'] !== true) {
                throw new \RuntimeException('Access Token is not valid.', 400);
            }

            $form['scopes'] = $tokenInfo['scopes'] ?? [];
            $form['userId'] = $tokenInfo['user_id'] ?? 'me';
            $form['expiresAt'] = $tokenInfo['data_access_expires_at'] ?? null;
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($form);
    }
}
