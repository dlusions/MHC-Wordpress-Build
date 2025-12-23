<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\TikTok;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\TikTok\TikTokApi;
use ZOOlanders\YOOessentials\Api\TikTok\TikTokApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\RenewTokenInterface;

class TikTokAuthDriver extends AuthDriver implements RenewTokenInterface
{
    protected const CLIENT_KEY = 'aw7gb56h2gkhl8a9';
    protected const CLIENT_SECRET = 'f41aee5ad1a58bf962828c71c9f13ed3';

    public function renewToken(AuthOAuth $auth): AuthOAuth
    {
        /** @var TikTokApi $api */
        $api = app(TikTokApiInterface::class);

        $clientId = $auth->custom ? $auth->clientId() : self::CLIENT_KEY;
        $clientSecret = $auth->custom ? $auth->clientSecret() : self::CLIENT_SECRET;

        if ($auth->custom && (empty($clientId) || empty($clientSecret))) {
            throw new \RuntimeException('Client ID and Secret must be provided.', 400);
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
