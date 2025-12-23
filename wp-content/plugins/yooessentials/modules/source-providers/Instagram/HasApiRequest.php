<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Instagram\InstagramApi;
use ZOOlanders\YOOessentials\Api\Instagram\InstagramApiInterface;

trait HasApiRequest
{
    public static function api(string $authId): ?InstagramApiInterface
    {
        try {
            /** @var AuthOAuth $auth */
            $auth = app(AuthManager::class)->auth($authId);

            return app(InstagramApi::class)->withAccessToken($auth->accessToken())->forDriver($auth->driverName());
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source-instagram',
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return null;
        }
    }
}
