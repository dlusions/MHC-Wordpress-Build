<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\LinkedIn;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\LinkedIn\LinkedInApi;
use ZOOlanders\YOOessentials\Api\LinkedIn\LinkedInApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\RenewTokenInterface;

class LinkedInAuthDriver extends AuthDriver implements RenewTokenInterface
{
    public function renewToken(AuthOAuth $auth): AuthOAuth
    {
        /** @var LinkedInApi $api */
        $api = app(LinkedInApiInterface::class);

        $clientId = $auth->clientId();
        $clientSecret = $auth->clientSecret();

        if (empty($clientId) || empty($clientSecret)) {
            throw new \RuntimeException('Client ID and Client Secret must be provided.', 400);
        }

        $data = $api->refreshAccessToken(
            $clientId,
            $clientSecret,
            $auth->refreshToken(),
        );

        return $auth
            ->setScopes($data['scope'] ?? $auth->scopes())
            ->setAccessToken($data['access_token'] ?? $auth->accessToken())
            ->setRefreshToken($data['refresh_token'] ?? $auth->refreshToken())
            ->setExpiresIn($data['expires_in'] ?? $auth->expiresIn());
    }
}
