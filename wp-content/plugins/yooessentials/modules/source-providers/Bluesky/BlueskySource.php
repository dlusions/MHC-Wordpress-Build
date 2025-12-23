<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky;

use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class BlueskySource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    use HasApiRequest;

    public function types(): array
    {
        return [
            new Type\BlueskyPostType(),
            new Type\BlueskyPostImageType(),
            new Type\BlueskyPostGifType(),
            new Type\BlueskyPostVideoType(),
            new Type\BlueskyFeedType(),
            new Type\BlueskyProfileType(),
            new Type\BlueskyActorProfileQueryType($this),
            new Type\BlueskyActorAuthorFeedQueryType($this),
            new Type\BlueskyActorListFeedQueryType($this),
            new Type\BlueskyActorPostsQueryType($this),
        ];
    }

    public function actor(): ?string
    {
        return $this->config()['actor'] ?? null;
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
