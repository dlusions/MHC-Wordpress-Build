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
use ZOOlanders\YOOessentials\Source\Provider\YouTube\YouTubeController;

class YouTubeChannelVideoQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'video';
    public const LABEL = 'Video';
    public const DESCRIPTION = 'Single video from the channel';

    public const MIN_CACHE_TIME = 3600;

    public static function getCacheKey(): string
    {
        return 'youtube-channel-video';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => YouTubeVideoType::NAME,

                    'args' => [
                        'video_id' => [
                            'type' => 'String',
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
                            'video_id' => [
                                'label' => 'Video',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'The video which content to fetch.',
                                'endpoint' => YouTubeController::GET_CHANNEL_VIDEOS_ENDPOINT,
                                'source' => true,
                                'params' => [
                                    'source_id' => $this->source->id(),
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

        $videoId = $args['video_id'] ?? null;

        if (!$videoId || !$source) {
            return;
        }

        return self::resolveFromCache($source, $args, fn () => $source->api()->video($videoId));
    }
}
