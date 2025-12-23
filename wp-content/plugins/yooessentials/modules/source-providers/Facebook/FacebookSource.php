<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;

class FacebookSource extends AbstractSourceType implements HasCacheTimes
{
    use HasApiRequest;

    public const MEDIA_LIMIT_DEFAULT = 20;

    public function types(): array
    {
        if (!self::api($this->account())) {
            return [];
        }

        return [
            new Type\FacebookPageType(),
            new Type\FacebookUserType(),
            new Type\FacebookPostType(),
            new Type\FacebookPlaceType(),
            new Type\FacebookPhotoType(),
            new Type\FacebookEventType(),
            new Type\FacebookReviewType(),
            new Type\FacebookPagePersonType(),
            new Type\FacebookStoryAttachment(),
            new Type\FacebookPageQueryType($this),
            new Type\FacebookPagePostsQueryType($this),
            new Type\FacebookPagePhotosQueryType($this),
            new Type\FacebookPageEventsQueryType($this),
            new Type\FacebookPageReviewsQueryType($this),
        ];
    }

    public function account(): ?string
    {
        return $this->config()['account'] ?? null;
    }

    public function pageId(): ?string
    {
        return $this->config()['page_id'] ?? null;
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        if (!$this->account()) {
            return HasCacheTimes::MIN_CACHE_TIME;
        }

        $auth = app(AuthManager::class)->auth($this->account());

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
