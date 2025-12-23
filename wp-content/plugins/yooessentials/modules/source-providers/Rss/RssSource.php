<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss;

use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssAuthorType;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssFilterType;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssOrderingType;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssEnclosureType;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssImageType;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssLinkType;

class RssSource extends AbstractSourceType implements HasCacheTimes
{
    use CachesResolvedSourceData;

    private ?RssFeed $rss = null;

    public function types(): array
    {
        try {
            $this->rss();
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'rss',
                'error' => $e->getMessage(),
            ]);

            return [];
        }

        $objectType = new Type\RssFeedType($this);
        $itemType = $objectType->itemType();
        $queryType = new Type\RssFeedQueryType($this, $objectType);
        $itemsQueryType = new Type\RssFeedItemsQueryType($this, $objectType);
        $filterType = new RssFilterType();
        $orderingType = new RssOrderingType();

        return array_merge(
            [
                $filterType,
                $orderingType,
                new DynamicSourceInputType($filterType),
                new DynamicSourceInputType($orderingType),
                new RssAuthorType(),
                new RssImageType(),
                new RssLinkType(),
                new RssEnclosureType(),
            ],
            $objectType->types(),
            $itemType->types(),
            [$objectType, $itemType, $queryType, $itemsQueryType]
        );
    }

    public function rss(): RssFeed
    {
        if ($this->rss !== null) {
            return $this->rss;
        }

        $url = $this->config('url');

        $cacheKey = self::getCacheKey() . sha1(json_encode($this->config()));
        $cacheTime = self::getCacheTime($this, []);

        /** @var RssService $rssService */
        $rssService = app(RssService::class);

        /** @var RssFeed $rssFeed */
        $rss = $rssService
            ->withCache($cacheTime, $cacheKey)
            ->load($url);

        return $this->rss = $rss;
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        return 0;
    }

    public static function getCacheKey(): string
    {
        return 'rss-source';
    }
}
