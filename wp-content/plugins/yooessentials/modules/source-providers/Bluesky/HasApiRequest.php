<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Api\Bluesky\BlueskyApiInterface;

trait HasApiRequest
{
    protected ?BlueskyApiInterface $api = null;

    public function api(): ?BlueskyApiInterface
    {
        if ($this->api !== null) {
            return $this->api;
        }

        try {
            return $this->api = app(BlueskyApiInterface::class);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source-bluesky',
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return null;
        }
    }
}
