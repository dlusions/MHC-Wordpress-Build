<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram\Type;

use ZOOlanders\YOOessentials\Source\Provider\Instagram\InstagramSource;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class InstagramHashtaggedMediaQueryType extends InstagramMediaQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const VIDEO_CACHE_TIME_DEFAULT = 3600;

    public const NAME = 'hashtaggedMedia';
    public const LABEL = 'Hashtagged Media';
    public const DESCRIPTION = 'List of media with a specific hashtag';

    public static function getCacheKey(): string
    {
        return 'instagram-hashtagged-media';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => InstagramMediaType::NAME,
                    ],

                    'args' => [
                        'hashtag' => [
                            'type' => 'String',
                        ],
                        'edge' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'since' => [
                            'type' => 'String',
                        ],
                        'until' => [
                            'type' => 'String',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => self::DESCRIPTION,
                        'fields' => [
                            '_media' => [
                                'type' => 'grid',
                                'description' => 'Set the Hashtag and Edge by which to fetch the media. Top Media fetched the most popular Media that have been tagged with the specified hashtag, while the Recent Media fetches the ones tagged in the last 24 hours.',
                                'width' => '1-2',
                                'fields' => [
                                    'hashtag' => [
                                        'label' => 'Hashtag',
                                        'source' => true,
                                    ],

                                    'edge' => [
                                        'type' => 'select',
                                        'label' => 'Edge',
                                        'default' => 'top_media',
                                        'options' => [
                                            'Top Media' => 'top_media',
                                            'Recent Media' => 'recent_media',
                                        ],
                                    ],
                                ],
                            ],

                            '_media_filter' => [
                                'type' => 'grid',
                                'description' => 'Choose the type and the maximum amount of media to fetch.',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Offset',
                                        'type' => 'yooessentials-number',
                                        'default' => 0,
                                        'source' => true,
                                    ],
                                    'limit' => [
                                        'label' => 'Limit',
                                        'type' => 'yooessentials-number',
                                        'default' => InstagramSource::MEDIA_LIMIT_DEFAULT,
                                        'source' => true,
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
                                'source_id' => $this->source()->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args): array
    {
        $hashtag = $args['hashtag'] ?? null;
        $edge = $args['edge'] ?? null;

        /** @var InstagramSource */
        $source = self::loadSource($args, InstagramSource::class);

        if (!$source || !$hashtag) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $source->api()->mediaByHashtag($source->pageId(), $hashtag, $edge, $args));
    }
}
