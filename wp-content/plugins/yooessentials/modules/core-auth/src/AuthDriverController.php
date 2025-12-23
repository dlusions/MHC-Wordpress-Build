<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class AuthDriverController
{
    public function presaveOAuth(Request $request, Response $response, AuthManager $authManager)
    {
        $form = $request->getParam('auth', []);

        $driver = $form['driver'] ?? null;
        $scopes = $form['scopes'] ?? [];
        $token = $form['refreshToken'] ?? null;

        if (empty($scopes)) {
            $scopes = $request->getParam('requiredScopes', []);
        }

        try {
            if (!$driver) {
                throw new \RuntimeException('Driver must be provided.', 400);
            }

            if (!$token) {
                throw new \RuntimeException('Refresh Token must be provided.', 400);
            }

            if (!$scopes) {
                throw new \RuntimeException('Scopes must be provided.', 400);
            }

            /** @var AuthDriver $authDriver */
            $authDriver = $authManager->driver($driver);

            /** @var AuthOAuth $auth */
            $auth = $authManager
                ->initAuth($form)
                ->forDriver($authDriver)
                ->setScopes($scopes);

            $auth = $authDriver->renewToken($auth);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($auth->withEncryptedKeys()->toArray(), 200);
    }
}
