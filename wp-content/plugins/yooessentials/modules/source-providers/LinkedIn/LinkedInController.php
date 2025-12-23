<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class LinkedInController
{
    use HasApiRequest;

    public const PRESAVE_ENDPOINT = 'yooessentials/source/linkedin';
    public const ORGS_ENDPOINT = 'yooessentials/source/linkedin/organizations';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $account = $form['account'] ?? null;
        $organization = $form['organization'] ?? null;

        if (!$account) {
            return $response->withJson('Account must be specified.', 400);
        }

        if (!$organization) {
            return $response->withJson('Organization must be specified.', 400);
        }

        return $response->withJson(200);
    }

    public function organizations(Request $request, Response $response, AuthManager $authManager)
    {
        $form = $request->getParam('form');
        $account = $form['account'] ?? null;
        $query = $request->getParam('query') ?? null;

        try {
            if (!($auth = $authManager->auth($account))) {
                throw new \Exception('Account not specified or invalid.');
            }

            $orgs = self::api($account)->organizations($auth->userId());
            $result = array_map(function ($org) use ($account) {
                $data = self::api($account)->organization($org['organization']);

                return [
                    'value' => $org['organization'],
                    'text' => $data['localizedName'] ?? $org['organization'],
                    'meta' => $data['id'] ?? null,
                ];
            }, $orgs);

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
}
