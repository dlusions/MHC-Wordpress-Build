<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Twitter;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\Twitter\TwitterApi;
use ZOOlanders\YOOessentials\Api\Twitter\TwitterApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\RenewTokenInterface;

class TwitterAuthDriver extends AuthDriver implements RenewTokenInterface
{
    public function renewToken(AuthOAuth $auth): AuthOAuth
    {
        /** @var TwitterApi $api */
        $api = app(TwitterApiInterface::class);

        $clientId = $auth->clientId();

        if (empty($clientId)) {
            throw new \RuntimeException('Client ID must be provided.', 400);
        }

        $data = $api->refreshAccessToken(
            $clientId,
            $auth->refreshToken(),
        );

        $auth = $auth
            ->setScopes($data['scope'] ?? $auth->scopes())
            ->setAccessToken($data['access_token'] ?? $auth->accessToken())
            ->setRefreshToken($data['refresh_token'] ?? $auth->refreshToken())
            ->setExpiresIn($data['expires_in'] ?? $auth->expiresIn());

        // persist userId in auth to avoid additional API calls later
        $userId = $api->withAccessToken($auth->accessToken)->account(['user.fields' => 'id'])['id'] ?? '';

        $auth->userId = $userId;

        return $auth;
    }
}
