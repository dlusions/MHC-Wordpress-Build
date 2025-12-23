<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube\Type;

use YOOtheme\Arr;
use YOOtheme\Url;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class YouTubeVideoType extends GenericType
{
    public const NAME = 'YouTubeVideo';
    public const LABEL = 'Video';

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
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveUrl',
                    ],
                ],
                'iframe' => [
                    'type' => 'String',
                    'args' => [
                        'autoplay' => [
                            'type' => 'Boolean',
                        ],
                        'loop' => [
                            'type' => 'Boolean',
                        ],
                        'mute' => [
                            'type' => 'Boolean',
                        ],
                        'controls' => [
                            'type' => 'Boolean',
                        ],
                        'width' => [
                            'type' => 'String',
                        ],
                        'height' => [
                            'type' => 'String',
                        ],
                        'start' => [
                            'type' => 'String',
                        ],
                        'end' => [
                            'type' => 'String',
                        ],
                        'hl' => [
                            'type' => 'String',
                        ],
                        'cc_lang_pref' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Iframe Player',
                        'arguments' => [
                            'autoplay' => [
                                'label' => 'Autoplay',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'description' => 'Whether the initial video will automatically start to play when the player loads.',
                            ],
                            'controls' => [
                                'label' => 'Controls',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'default' => true,
                                'description' => 'Whether the video players controls are displayed.',
                            ],
                            'loop' => [
                                'label' => 'Loop',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'description' => 'Whether to play the video again and again once it reach the end.',
                            ],
                            'mute' => [
                                'label' => 'Mute',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'description' => 'Whether the audio to be initially silenced.',
                            ],
                            'width' => [
                                'label' => 'Width',
                                'attrs' => [
                                    'placeholder' => 640,
                                ],
                                'description' => 'The width of the video player, defaults to 640.',
                            ],
                            'height' => [
                                'label' => 'Height',
                                'attrs' => [
                                    'placeholder' => 390,
                                ],
                                'description' => 'The height of the video player, defaults to 390.',
                            ],
                            'start' => [
                                'label' => 'Start Time',
                                'description' => 'Start playing the video at the given number of seconds from the start of the video, note that the player will look for the closest keyframe to the time you specify.',
                                'attrs' => [
                                    'placeholder' => '0',
                                ],
                            ],
                            'end' => [
                                'label' => 'End Time',
                                'description' => 'Stop playing the video at the given number of seconds from the start of the video.',
                            ],
                            'hl' => [
                                'label' => 'Interface Language',
                                'description' => 'The language that the player will use for it interface as an ISO 639-1 two-letter language code.',
                            ],
                            'cc_lang_pref' => [
                                'label' => 'Captions Language',
                                'description' => 'The prefered language that the player will use to display captions as an ISO 639-1 two-letter language code.',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveIframe',
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
                'commentCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Comments',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'statistics.commentCount',
                            ],
                        ],
                    ],
                ],
                'favoriteCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Favorites',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'statistics.favoriteCount',
                            ],
                        ],
                    ],
                ],
                'likesCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Likes',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'statistics.likesCount',
                            ],
                        ],
                    ],
                ],
                'dislikeCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Dislikes',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'statistics.dislikeCount',
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

    public static function resolveProp(array $video, array $args)
    {
        return Arr::get($video, $args['path']);
    }

    public static function resolveUrl(array $video)
    {
        return "https://www.youtube.com/watch?v={$video['id']}";
    }

    public static function resolveIframe(array $video, array $args)
    {
        $args = array_filter($args);

        $width = $args['width'] ?? 640;
        $height = $args['height'] ?? 390;

        // workaround for single video loop support
        if ($args['loop'] ?? false) {
            $args['playlist'] = $video['id'];
        }

        $url = Url::to(
            "https://www.youtube-nocookie.com/embed/{$video['id']}",
            array_merge(
                [
                    'rel' => 0,
                    'controls' => 0,
                    'playsinline' => 1,
                    'iv_load_policy' => 3,
                    'modestbranding' => 1,
                ],
                $args
            )
        );

        return sprintf(
            '<iframe id="player" type="text/html" src="%s" width="%s" height="%s" frameborder="0"></iframe>',
            $url,
            $width,
            $height
        );
    }

    public static function resolveThumbnailUrl(array $video, array $args): string
    {
        $id = $video['id'] ?? '';
        $size = $args['size'] ?? self::DEFAULT_THUMB_SIZE;
        $url = $video['snippet']['thumbnails'][$size]['url'] ?? '';

        if ($id && $url) {
            return Util\File::cacheMedia($url, "youtube-video-thumb-{$id}-{$size}");
        }

        return '';
    }

    public static function resolveThumbnailWidth(array $video, array $args): int
    {
        $size = $args['size'] ?? self::DEFAULT_THUMB_SIZE;

        return $video['snippet']['thumbnails'][$size]['width'] ?? 0;
    }

    public static function resolveThumbnailHeight(array $video, array $args): int
    {
        $size = $args['size'] ?? self::DEFAULT_THUMB_SIZE;

        return $video['snippet']['thumbnails'][$size]['height'] ?? 0;
    }
}
