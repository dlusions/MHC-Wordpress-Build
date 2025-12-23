<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Airtable;

use YOOtheme\Str;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Airtable\AirtableApiInterface;

class AirtableAuthDriverController
{
    public function presave(Request $request, Response $response, AuthManager $authManager, AirtableApiInterface $api)
    {
        $form = $request->getParam('auth', []);

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

            /** @var AuthOAuth $auth */
            $auth = $authManager
                ->initAuth($form)
                ->forDriver($authDriver);

            $tokenInfo = $api->withAccessToken($auth->accessToken)->me();

            if (!isset($tokenInfo['id'])) {
                throw new \RuntimeException('Access Token is not valid.', 400);
            }

            $form['userId'] = $tokenInfo['id'];
        } catch (\Throwable $e) {
            $message = $e->getMessage();

            if (Str::startsWith($message, 'Authentication required')) {
                $message = 'Access Token is not valid.';
            }

            return $response->withJson($message, 400);
        }

        return $response->withJson($form);
    }
}
