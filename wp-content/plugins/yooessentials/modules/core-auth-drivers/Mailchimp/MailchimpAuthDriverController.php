<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Mailchimp;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Mailchimp\MailchimpApiInterface;

class MailchimpAuthDriverController
{
    /**
     * @var string
     */
    public const PRE_OAUTH_SAVE_ENDPOINT = 'yooessentials/auth/mailchimp/oauth';
    public const PRE_APIKEY_SAVE_ENDPOINT = 'yooessentials/auth/mailchimp/apikey';

    public function presaveOauth(Request $request, Response $response, AuthManager $authManager, MailchimpApiInterface $api)
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

            $tokenInfo = $api->getOauthMetadata($auth->accessToken);

            $form['dc'] = $tokenInfo['dc'] ?? '';
            $form['account'] = $tokenInfo['accountname'] ?? '';

            if (!$form['dc']) {
                throw new \Exception('Error at retrieving the user Data Center prefix.');
            }
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($form, 200);
    }

    public function presaveApikey(Request $request, Response $response, MailchimpApiInterface $api)
    {
        $form = $request->getParam('auth', []);
        $key = $form['key'] ?? null;

        try {
            if (!$key) {
                throw new \Exception('Missing API key.');
            }

            if (!Str::contains($key, '-')) {
                throw new \Exception('Invalid Key format.');
            }

            $tokenInfo = $api->withApiKey($key)->getAccountMetadata();

            $form['account'] = $tokenInfo['account_name'] ?? '';
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($form, 200);
    }
}
