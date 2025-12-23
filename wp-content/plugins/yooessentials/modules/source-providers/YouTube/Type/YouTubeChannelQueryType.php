<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\YouTube\YouTubeChannelSource;

class YouTubeChannelQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const MIN_CACHE_TIME = 3600;

    public const NAME = 'channel';
    public const LABEL = 'Channel';
    public const DESCRIPTION = 'Channel data';

    public static function getCacheKey(): string
    {
        return 'youtube-channel';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => YouTubeChannelType::NAME,

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
                            'args' => [
                                'source_id' => $this->source->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        /** @var YouTubeChannelSource */
        $source = self::loadSource($args, YouTubeChannelSource::class);

        if (!$source) {
            return;
        }

        return self::resolveFromCache($source, $args, fn () => $source->api()->channel($source->channel, ['part' => 'snippet,statistics']));
    }
}
