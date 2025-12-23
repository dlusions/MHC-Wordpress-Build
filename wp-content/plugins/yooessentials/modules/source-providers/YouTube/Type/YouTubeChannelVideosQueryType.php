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

class YouTubeChannelVideosQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'videos';
    public const LABEL = 'Videos';
    public const DESCRIPTION = 'List of videos from the channel';

    public const DEFAULT_MAX_RESULTS = 20;
    public const MIN_CACHE_TIME = 3600;

    public static function getCacheKey(): string
    {
        return 'youtube-channel-videos';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => YouTubeVideoType::NAME,
                    ],

                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'maxResults' => [
                            'type' => 'Int',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            '_offset' => [
                                'description' => 'The starting point and the maximum amount of videos to retrieve.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                    'maxResults' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => self::DEFAULT_MAX_RESULTS,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 50,
                                        ],
                                    ],
                                ],
                            ],
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
            return [];
        }

        return self::resolveFromCache($source, $args, function () use ($source, $args) {
            $offset = $args['offset'] ?? 0;
            $maxResults = $offset + ($args['maxResults'] ?? self::DEFAULT_MAX_RESULTS);

            $result = $source->api()->channelVideos($source->channel, ['maxResults' => $maxResults]);

            return array_splice($result, $offset);
        });
    }
}
