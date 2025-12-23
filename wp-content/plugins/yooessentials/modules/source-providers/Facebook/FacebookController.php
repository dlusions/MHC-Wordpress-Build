<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class FacebookController
{
    use HasApiRequest;

    public const PAGES_ENDPOINT = 'yooessentials/source/facebook/pages';
    public const PRESAVE_ENDPOINT = 'yooessentials/source/facebook';

    public function pages(Request $request, Response $response, AuthManager $authManager)
    {
        $form = $request->getParam('form');
        $account = $form['account'] ?? null;
        $query = $request->getParam('query') ?? null;

        try {
            if (!($auth = $authManager->auth($account))) {
                throw new \Exception('Account not specified or invalid.');
            }

            $pages = self::api($account)->pages($auth->userId());

            $result = array_map(fn ($page) => [
                'value' => $page['id'],
                'meta' => $page['id'],
                'text' => $page['name'] ?? $page['id'],
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
        $page = $form['page_id'] ?? null;

        if (!($auth = $authManager->auth($account))) {
            return $response->withJson('Account must be specified.', 400);
        }

        if (!$page) {
            return $response->withJson('Page must be specified.', 400);
        }

        $pages = self::api($account)->pages($auth->userId());

        if (!in_array($page, array_column($pages, 'id'))) {
            return $response->withJson('Page must be owned by you.', 400);
        }

        return $response->withJson(200);
    }
}
