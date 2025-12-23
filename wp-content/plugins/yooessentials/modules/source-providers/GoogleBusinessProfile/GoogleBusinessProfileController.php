<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile;

use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\Google\GoogleBusinessProfileApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class GoogleBusinessProfileController
{
    use LoadsSourceFromArgs;

    public const PRESAVE_ENDPOINT = 'yooessentials/source/google-business-profile';
    public const GET_LOCATIONS_ENDPOINT = 'yooessentials/source/google-business-profile/locations';
    public const GET_ACCOUNTS_ENDPOINT = 'yooessentials/source/google-business-profile/accounts';
    public const GET_REVIEWS_ENDPOINT = 'yooessentials/source/google-business-profile/reviews';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $account = $form['account'] ?? null;
        $location = $form['location'] ?? null;
        $businessAccount = $form['businessAccount'] ?? null;

        try {
            if (!$account) {
                throw new \Exception('Account must be specified');
            }

            if (!$businessAccount) {
                throw new \Exception('Business Account must be specified');
            }

            if (!$location) {
                throw new \Exception('Location must be specified');
            }
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(200);
    }

    public function accounts(Request $request, Response $response)
    {
        $form = $request->getParam('form');

        try {
            $authManager = app(AuthManager::class);
            $auth = $authManager->auth($form['account']);
            $api = app(GoogleBusinessProfileApiInterface::class)->withAccessToken($auth->accessToken());

            $accounts = array_map(fn ($account) => [
                'text' => $account['accountName'],
                'value' => $account['name'],
                'meta' => $account['name'],
            ], $api->accounts());

            return $response->withJson($accounts, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function locations(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $query = $request->getParam('query');
        $businessAccount = $form['businessAccount'] ?? null;

        try {
            if (!$businessAccount) {
                throw new \Exception('Business Account must be specified');
            }

            $authManager = app(AuthManager::class);
            $auth = $authManager->auth($form['account']);
            $api = app(GoogleBusinessProfileApiInterface::class)->withAccessToken($auth->accessToken());

            $args = ['pageSize' => 50];

            if (!empty($query)) {
                $args['filter'] = "title:\"{$query}\"";
            }

            $locations = array_map(fn ($location) => [
                'value' => $location['name'],
                'meta' => $location['name'],
                'text' => $location['title'],
            ], $api->locations($businessAccount, $args));

            return $response->withJson($locations, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function reviews(Request $request, Response $response)
    {
        /** @var GoogleBusinessProfileSource */
        $source = self::loadSource($request->getParsedBody(), GoogleBusinessProfileSource::class);

        try {
            $reviews = $source->api()->reviews($source->businessAccount, $source->location)['reviews'] ?? [];

            $items = array_map(function ($review) {
                $name = $review['name'] ?? '';
                $updatedAt = new \DateTime($review['updateTime']);
                $reviewer = $review['reviewer']['displayName'] ?? '';
                $comment = Str::limit($review['comment'] ?? '', 80);

                return [
                    'value' => $name,
                    'text' => $reviewer . ' - ' . $updatedAt->format('jS M y'),
                    'meta' => $comment,
                ];
            }, $reviews);

            return $response->withJson($items, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
