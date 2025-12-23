<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Vimeo;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\Vimeo\VimeoApi;
use ZOOlanders\YOOessentials\Api\Vimeo\VimeoApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthDriver;
use ZOOlanders\YOOessentials\Auth\RenewTokenInterface;

class VimeoAuthDriver extends AuthDriver implements RenewTokenInterface
{
    protected const CLIENT_KEY = 'aw7gb56h2gkhl8a9';
    protected const CLIENT_SECRET = 'f41aee5ad1a58bf962828c71c9f13ed3';

    public function renewToken(AuthOAuth $auth): AuthOAuth
    {
        /** @var VimeoApi $api */
        $api = app(VimeoApiInterface::class);

        $data = $api->verifyToken($auth->accessToken());

        return $auth
            ->setScopes($data['scope'] ?? $auth->scopes())
            ->setAccessToken($data['access_token'] ?? $auth->accessToken());
    }
}
