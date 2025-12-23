<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

use YOOtheme\Event;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Component\Cache\Adapter\FilesystemAdapter;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\ItemInterface;
use ZOOlanders\YOOessentials\Vendor\Psr\Cache\InvalidArgumentException;
use function YOOtheme\app;

trait CachesResolvedSourceData
{
    abstract public static function getCacheKey(): string;

    protected function cacheField(): array
    {
        return [
            'type' => 'yooessentials-number',
            'label' => 'Cache Time',
            'description' => $this->source instanceof HasCacheTimes
                ? sprintf('The duration in seconds before the cache is renewed, it overrides the default cache set in the source configuration.', $this->source->minCacheTime())
                : 'The duration in seconds before the cache is renewed, set to <code>0</code> to disable. It overrides default cache set in the source configuration.',
            'attrs' => [
                'min' => $this->source instanceof HasCacheTimes
                    ? $this->source->minCacheTime()
                    : 0,
                'max' => 86400 * 30,
                'step' => 3600,
                'placeholder' => $this->source instanceof HasCacheTimes
                    ? $this->source->config('cache_default') ?? $this->source->defaultCacheTime()
                    : HasCacheTimes::DEFAULT_CACHE_TIME,
            ],
        ];
    }

    /**
     * This may be called with
     * resolveCache($source, $args, function()
     * resolveCache($source, $args, $root, function())
     *
     * The first is kept for backwards compatibility
     *
     * @param SourceInterface $source
     * @param array $args
     * @param \Closure $callback
     * @return array|null|mixed
     * @throws InvalidArgumentException
     */
    public static function resolveFromCache(SourceInterface $source, array $args, $root, $callback = null)
    {
        // Root may or may not be passed.
        if ($callback === null) {
            $callback = $root;
            $root = [];
        }

        // Pre-resolve the arguments so they get cached correctly
        $args = static::resolveArgs($args, $root);
        $cacheKey = static::getCacheKey() . sha1(json_encode($args + $source->config()));
        $cacheTime = static::getCachetime($source, $args);

        /** @var FilesystemAdapter $cache */
        $cache = app(CacheInterface::class);

        if ($cacheTime <= 0) {
            $cache->delete($cacheKey);
        }

        $records = $cache->get($cacheKey, function (ItemInterface $item) use ($source, $callback, $args, $cacheTime) {
            $item->expiresAfter($cacheTime);

            try {
                return $callback();
            } catch (\Throwable $e) {
                Event::emit('yooessentials.error', [
                    'addon' => 'source',
                    'source' => "{$source->name()} ({$source->metadata()->name})",
                    'action' => 'source-query-resolve',
                    'args' => $args,
                    'error' => $e->getMessage(),
                    'exception' => $e,
                    'trace' => json_encode($e->getTrace() ?? []),
                ]);
            }

            return [];
        });

        // avoid caching empty list
        if (empty($records)) {
            $cache->delete($cacheKey);
        }

        return $records;
    }

    protected static function getCacheTime(SourceInterface $source, array $args): int
    {
        $min = $source instanceof HasCacheTimes ? $source->minCacheTime() : 0;

        $defaultCache = $source instanceof HasCacheTimes ? $source->defaultCacheTime() : HasCacheTimes::DEFAULT_CACHE_TIME;
        $cache = (int) ($args['cache'] ?? $defaultCache);

        if ($cache < 0) {
            $cache = 0;
        }

        if ($cache < $min) {
            $cache = $min;
        }

        return $cache;
    }

    /**
     * Override this method if the args contains dynamic properties (ie: sources)
     */
    protected static function resolveArgs(array $args, $root): array
    {
        return $args;
    }
}
