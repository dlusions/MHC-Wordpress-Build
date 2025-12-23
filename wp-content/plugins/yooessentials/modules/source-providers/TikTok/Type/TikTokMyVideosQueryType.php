<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\TikTok\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\TikTok\TikTokSource;
use ZOOlanders\YOOessentials\Util;

class TikTokMyVideosQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'videos';
    public const LABEL = 'Videos';
    public const DESCRIPTION = 'List of videos';

    public static function getCacheKey(): string
    {
        return 'tiktok-my-videos';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => TikTokVideoType::NAME,
                    ],

                    'args' => [
                        'video_ids' => [
                            'type' => 'String',
                        ],
                        'cursor' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
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
                            'video_ids' => [
                                'label' => 'Filter by ID',
                                'description' => 'Filter videos by comma separated list of IDs, up to maximum 20 videos.',
                                'source' => true,
                            ],
                            'cursor' => [
                                'label' => 'Filter by date',
                                'description' => 'Filter videos created before the specified date.',
                                'small' => true,
                                'attrs' => [
                                    'type' => 'date',
                                ],
                                'enable' => '!video_ids'
                            ],
                            '_offset_limit' => [
                                'description' => 'Set the starting point and limit the number of videos.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'modifier' => 1,
                                        'default' => 0,
                                        'source' => true,
                                        'enable' => '!video_ids',
                                        'attrs' => [
                                            'min' => 1,
                                            'placeholder' => 1,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => 20,
                                        'source' => true,
                                        'enable' => '!video_ids',
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 20,
                                            'placeholder' => TikTokSource::VIDEO_LIMIT_DEFAULT,
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
        /** @var TikTokSource */
        $source = self::loadSource($args, TikTokSource::class);

        if (!$source) {
            return [];
        }

        return self::resolveFromCache($source, $args, function () use ($source, $args) {
            if (!empty($args['video_ids'])) {
                return $source->api()->queryVideos([
                    'video_ids' => Util\Arr::trim(explode(',', $args['video_ids'] ?? ''))
                ]);
            }

            $videos = $source->api()->listVideos([
                'cursor' => isset($args['cursor']) ? self::getTimestamp($args['cursor']) : null,
            ]);

            return array_slice($videos, abs($args['offset'] ?? 0), $args['limit'] ?? null);
        });
    }

    protected static function getTimestamp(string $date): int
    {
        return round((new \DateTime($date))->getTimestamp() * 1000);
    }
}
