<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube\Type;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class YouTubeChannelType extends GenericType
{
    public const NAME = 'YouTubeChannelType';
    public const LABEL = 'Channel';

    const DEFAULT_THUMB_SIZE = 'high';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'snippet.title',
                            ],
                        ],
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'snippet.description',
                            ],
                        ],
                    ],
                ],
                'country' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Country',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'snippet.country',
                            ],
                        ],
                    ],
                ],
                'publishedAt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Published At',
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'snippet.publishedAt',
                            ],
                        ],
                    ],
                ],
                'thumbnail_url' => [
                    'type' => 'String',
                    'args' => [
                        'size' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Thumbnail URL',
                        'arguments' => [
                            'size' => [
                                'label' => 'Size',
                                'type' => 'select',
                                'default' => self::DEFAULT_THUMB_SIZE,
                                'options' => [
                                    'Low' => 'default',
                                    'Medium' => 'medium',
                                    'High Resolution' => 'high',
                                    'Standard' => 'standard',
                                    'Max Resolution' => 'maxres',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnailUrl',
                    ],
                ],
                'thumbnail_width' => [
                    'type' => 'String',
                    'args' => [
                        'size' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Thumbnail Width',
                        'arguments' => [
                            'size' => [
                                'label' => 'Size',
                                'type' => 'select',
                                'default' => self::DEFAULT_THUMB_SIZE,
                                'options' => [
                                    'Low' => 'default',
                                    'Medium' => 'medium',
                                    'High Resolution' => 'high',
                                    'Standard' => 'standard',
                                    'Max Resolution' => 'maxres',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnailWidth',
                    ],
                ],
                'thumbnail_height' => [
                    'type' => 'String',
                    'args' => [
                        'size' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Thumbnail Height',
                        'arguments' => [
                            'size' => [
                                'label' => 'Size',
                                'type' => 'select',
                                'default' => self::DEFAULT_THUMB_SIZE,
                                'options' => [
                                    'Low' => 'default',
                                    'Medium' => 'medium',
                                    'High Resolution' => 'high',
                                    'Standard' => 'standard',
                                    'Max Resolution' => 'maxres',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnailHeight',
                    ],
                ],
                'viewCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Views',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'statistics.viewCount',
                            ],
                        ],
                    ],
                ],
                'videoCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Videos',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'statistics.videoCount',
                            ],
                        ],
                    ],
                ],
                'subscriberCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Subscribers',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'statistics.subscriberCount',
                            ],
                        ],
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],
        ];
    }

    public static function resolveProp(array $channel, array $args)
    {
        return Arr::get($channel, $args['path']);
    }

    public static function resolveThumbnailUrl(array $channel, array $args): string
    {
        $id = $channel['id'] ?? '';
        $size = $args['size'] ?? self::DEFAULT_THUMB_SIZE;
        $url = $channel['snippet']['thumbnails'][$size]['url'] ?? '';

        if ($id && $url) {
            return Util\File::cacheMedia($url, "youtube-channel-thumb-{$id}-{$size}");
        }

        return '';
    }

    public static function resolveThumbnailWidth(array $channel, array $args): int
    {
        $size = $args['size'] ?? self::DEFAULT_THUMB_SIZE;

        return $channel['snippet']['thumbnails'][$size]['width'] ?? 0;
    }

    public static function resolveThumbnailHeight(array $channel, array $args): int
    {
        $size = $args['size'] ?? self::DEFAULT_THUMB_SIZE;

        return $channel['snippet']['thumbnails'][$size]['height'] ?? 0;
    }
}
