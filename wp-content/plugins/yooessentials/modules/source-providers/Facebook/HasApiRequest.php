<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Api\Facebook\FacebookApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;

trait HasApiRequest
{
    public static function api(string $authId): ?FacebookApiInterface
    {
        try {
            $auth = app(AuthManager::class)->auth($authId);

            if (!$auth) {
                return null;
            }

            return app(FacebookApiInterface::class)->withAccessToken($auth->accessToken());
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source-facebook',
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return null;
        }
    }
}
