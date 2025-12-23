<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;

class InstagramController
{
    use HasApiRequest;

    /** @var string */
    public const PAGES_ENDPOINT = 'yooessentials/source/instagram/pages';
    public const PRESAVE_ENDPOINT = 'yooessentials/source/instagram';

    public function pages(Request $request, Response $response, AuthManager $authManager)
    {
        $form = $request->getParam('form');
        $query = $request->getParam('query') ?? null;
        $account = $form['account'] ?? null;

        try {
            if (!($auth = $authManager->auth($account))) {
                throw new \Exception('Account not specified or invalid.');
            }

            /** @var AuthOAuth $auth */
            switch ($auth->driverName()) {
                case 'facebook':
                    $pages = self::api($account)
                        ->pages($auth->userId());

                    break;
                default:
                    $pages = [];

                    break;
            }

            $result = array_map(fn ($page) => [
                'text' => $page['name'] ?? $page['id'],
                'value' => $page['id'],
                'meta' => $page['id'],
            ], $pages);

            if ($query) {
                $result = array_values(
                    array_filter($result, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text']))
                );
            }

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function presave(Request $request, Response $response, AuthManager $authManager)
    {
        $form = $request->getParam('form') ?? [];
        $account = $form['account'] ?? null;

        try {
            if (!$account) {
                throw new \Exception('Account must be specified.');
            }

            $page = $form['page_id'] ?? null;
            $auth = $authManager->auth($account);

            if (!$auth) {
                throw new \Exception('Invalid Auth.');
            }

            if ($auth->driverName() === 'facebook' and !$page) {
                throw new \Exception('Page must be specified.');
            }
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(200);
    }
}
