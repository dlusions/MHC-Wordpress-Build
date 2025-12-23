<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream;

use function YOOtheme\app;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\Cloudflare\CloudflareApi;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class CloudflareController
{
    public const GET_ACCOUNTS_ENDPOINT = 'yooessentials/source/cloudflare/accounts';

    public function getAccounts(Request $request, Response $response, AuthManager $authManager)
    {
        $form = $request->getParam('form') ?? [];

        try {
            $token = $form['token'] ?? null;
            $auth = $authManager->auth($token);

            if (!$token) {
                throw new \Exception('Token must be specified.');
            }

            if (!$auth) {
                throw new \Exception('Invalid Auth.');
            }

            if (!$auth->accessToken) {
                throw new \Exception('Access Token must be specified.');
            }

            $api = app(CloudflareApi::class)->withAccessToken($auth->accessToken);

            $items = array_map(fn ($account) => [
                'text' => $account['name'],
                'value' => $account['id'],
                'meta' => $account['id'],
            ], $api->accounts());

            if (empty($items)) {
                throw new \Exception(
                    'The API Token is missing the Account Settings Read permissions for this operation.'
                );
            }

            return $response->withJson($items, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
