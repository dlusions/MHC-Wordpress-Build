<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Google;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\Google\GoogleApi;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\RenewTokenInterface;

class GoogleAuthDriver extends AuthDriver implements RenewTokenInterface
{
    public const CLIENT_ID = '1084661277420-9jleq1k3bmam11ibd7sq6figa0phabi1.apps.googleusercontent.com';
    public const CLIENT_SECRET = 'Tl6XAv0vw9NHq3CXT_uda5Kp';

    public function renewToken(AuthOAuth $auth): AuthOAuth
    {
        /** @var GoogleApi $api */
        $api = app(GoogleApi::class);

        $clientId = $auth->custom ? $auth->clientId() : self::CLIENT_ID;
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
