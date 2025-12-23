<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\LinkedIn\LinkedInApiInterface;

trait HasApiRequest
{
    /** @var LinkedInApiInterface[] */
    protected array $api = [];

    public function api(string $authId): ?LinkedInApiInterface
    {
        if (isset($this->api[$authId])) {
            return $this->api[$authId];
        }

        try {
            $auth = app(AuthManager::class)->auth($authId);

            if (!$auth) {
                return null;
            }

            return $this->api[$authId] = app(LinkedInApiInterface::class)->withAccessToken($auth->accessToken);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source-linkedin',
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return null;
        }
    }
}
