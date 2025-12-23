<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSource;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\HasDynamicArgs;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Rss\RssSource;
use ZOOlanders\YOOessentials\Util\Arr;

class RssFeedQueryType extends AbstractQueryType
{
    use CachesResolvedSourceData, LoadsSourceFromArgs, HasDynamicArgs, HasSource;

    private RssFeedType $rssType;

    public const NAME = 'feed';
    public const LABEL = 'Feed';
    public const DESCRIPTION = 'The feed data';

    public function __construct(SourceInterface $source, RssFeedType $rssType)
    {
        $this->source = $source;
        $this->rssType = $rssType;
    }

    public function config(): array
    {
        $args = [
            'source_id' => $this->source->id(),
        ];

        if (!$this->source->id()) {
            $args = array_merge($args, $this->source->config());
        }

        return [
            'fields' => [
                $this->name() => [
                    'type' => $this->rssType->name(),

                    'args' => [
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => $args,
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args): array
    {
        /** @var RssSource */
        $source = self::loadSource($args, RssSource::class);

        if (!$source) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $source->rss()->toArray());
    }

    public static function getCacheKey(): string
    {
        return 'rss-feed';
    }

    protected static function resolveArgs(array $args, $root): array
    {
        $dynamicArgs = ['filters', 'ordering', 'orderings'];

        foreach ($dynamicArgs as $arg) {
            $args[$arg] = Arr::map($args[$arg] ?? [], function ($dynamic) use ($root) {
                if (!isset($dynamic['source'])) {
                    return $dynamic;
                }

                return self::resolveDynamicArguments($dynamic, $root);
            });
        }

        return $args;
    }
}
