<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Vimeo\Type;

use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Arr;
use YOOtheme\Url;
use YOOtheme\Path;
use YOOtheme\View;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class VimeoVideoType extends GenericType
{
    public const NAME = 'VimeoVideo';
    public const LABEL = 'Video';

    public const DEFAULT_WIDTH = 800;
    public const DEFAULT_HEIGHT = 450;

    public const FIELDS = [
        'uri',
        'name',
        'description',
        'resource_key',
        'type',
        'pictures.base_link',
        'link',
        'custom_url',
        'stats.plays',
        'created_time',
        'release_time',
        'modified_time',
        'duration',
        'height',
        'width',
        'language',
        'license',
        'metadata.connections.likes.total',
        'metadata.connections.comments.total',
    ];

    public function config(): array
    {
        return [
            'fields' => [
                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                ],
                'custom_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Custom URL',
                    ],
                ],
                'type' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                    ],
                ],
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'filters' => ['limit'],
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'duration' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Duration (sec)',
                    ],
                ],
                'language' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Language',
                    ],
                ],
                'license' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'License',
                    ],
                ],
                'thumbnail' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Thumbnail',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnail',
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Width',
                    ],
                ],
                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height',
                    ],
                ],
                'tagsString' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Tags',
                        'arguments' => [
                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'Set the separator between.',
                                'default' => ', ',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveTagsString',
                    ],
                ],
                'categoriesString' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                        'show_link' => [
                            'type' => 'Boolean',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Categories',
                        'arguments' => [
                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'Set the separator between.',
                                'default' => ', ',
                            ],
                            'show_link' => [
                                'label' => 'Link',
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => 'Show link',
                            ],
                            'link_style' => [
                                'label' => 'Link Style',
                                'description' => 'Set the link style.',
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'Default' => '',
                                    'Muted' => 'link-muted',
                                    'Text' => 'link-text',
                                    'Heading' => 'link-heading',
                                    'Reset' => 'link-reset',
                                ],
                                'enable' => 'arguments.show_link',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveCategoriesString',
                    ],
                ],
                'created_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'release_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Released At',
                        'filters' => ['date'],
                    ],
                ],
                'modified_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Modified At',
                        'filters' => ['date'],
                    ],
                ],
                'plays_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Plays',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'stats.plays',
                            ],
                        ],
                    ],
                ],
                'likes_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Likes',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'metadata.connections.likes.total',
                            ],
                        ],
                    ],
                ],
                'comments_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Comments',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'metadata.connections.comments.total',
                            ],
                        ],
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
                        'muted' => [
                            'type' => 'Boolean',
                        ],
                        'hide_pip' => [
                            'type' => 'Boolean',
                        ],
                        'hide_title' => [
                            'type' => 'Boolean',
                        ],
                        'hide_portrait' => [
                            'type' => 'Boolean',
                        ],
                        'hide_byline' => [
                            'type' => 'Boolean',
                        ],
                        'hide_controls' => [
                            'type' => 'Boolean',
                        ],
                        'dnt' => [
                            'type' => 'Boolean',
                        ],
                        'responsive' => [
                            'type' => 'Boolean',
                        ],
                        'transparent' => [
                            'type' => 'Boolean',
                        ],
                        'width' => [
                            'type' => 'String',
                        ],
                        'height' => [
                            'type' => 'String',
                        ],
                        'color' => [
                            'type' => 'String',
                        ],
                        'start_at' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Iframe Player',
                        'arguments' => [
                            'autoplay' => [
                                'label' => 'Autoplay',
                                'type' => 'checkbox',
                                'text' => 'Play on load',
                            ],
                            'loop' => [
                                'label' => 'Loop',
                                'type' => 'checkbox',
                                'text' => 'Play again and again',
                            ],
                            'muted' => [
                                'label' => 'Mute',
                                'type' => 'checkbox',
                                'text' => 'Mute Audio',
                            ],
                            'dnt' => [
                                'label' => 'DNT',
                                'type' => 'checkbox',
                                'text' => 'Do not track user',
                            ],
                            'responsive' => [
                                'label' => 'Responsive',
                                'type' => 'checkbox',
                                'text' => 'Responsive embed code',
                            ],
                            'transparent' => [
                                'label' => 'Transparent',
                                'type' => 'checkbox',
                                'text' => 'Transparent background',
                            ],
                            'hide_controls' => [
                                'label' => 'Controls',
                                'type' => 'checkbox',
                                'text' => 'Disable player controls',
                            ],
                            'hide_pip' => [
                                'label' => 'PIP',
                                'type' => 'checkbox',
                                'text' => 'Disable picture-in-picture',
                            ],
                            'hide_title' => [
                                'label' => 'Title',
                                'type' => 'checkbox',
                                'text' => 'Hide Video title',
                            ],
                            'hide_portrait' => [
                                'label' => 'Portrait',
                                'type' => 'checkbox',
                                'text' => 'Hide Owner\'s portrait',
                            ],
                            'hide_byline' => [
                                'label' => 'Name',
                                'type' => 'checkbox',
                                'text' => 'Hide',
                                'text' => 'Hide Owner\'s name',
                            ],
                            'width' => [
                                'label' => 'Width',
                                'attrs' => [
                                    'placeholder' => self::DEFAULT_WIDTH,
                                ],
                            ],
                            'height' => [
                                'label' => 'Height',
                                'attrs' => [
                                    'placeholder' => self::DEFAULT_HEIGHT,
                                ],
                            ],
                            'color' => [
                                'label' => 'Color (hex)',
                                'attrs' => [
                                    'placeholder' => '00ADEF',
                                ],
                            ],
                            'start_at' => [
                                'label' => 'Start At (sec)',
                                'attrs' => [
                                    'placeholder' => '0',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveIframe',
                        ],
                    ],
                ],
                'user' => [
                    'type' => 'VimeoUser',
                    'metadata' => [
                        'label' => 'Author',
                    ],
                ],
                'categories' => [
                    'type' => [
                        'listOf' => 'VimeoCategory',
                    ],
                    'metadata' => [
                        'label' => 'Categories',
                    ],
                ],
                'tags' => [
                    'type' => [
                        'listOf' => 'VimeoTag',
                    ],
                    'metadata' => [
                        'label' => 'Tags',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveId',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function fields()
    {
        return implode(
            ',',
            array_merge(
                self::FIELDS,
                preg_filter('/^/', 'tags.', VimeoTagType::FIELDS),
                preg_filter('/^/', 'user.', VimeoUserType::FIELDS),
                preg_filter('/^/', 'categories.', VimeoCategoryType::FIELDS)
            )
        );
    }

    public static function resolveId(array $video): ?string
    {
        return explode('/', trim($video['uri'], '/'))[1] ?? null;
    }

    public static function resolveProp(array $video, array $args)
    {
        return Arr::get($video, $args['path']);
    }

    public static function resolveCategoriesString(array $video, array $args)
    {
        $items = $video['categories'];
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];

        return app(View::class)->render(Path::get('./templates/list'), ['items' => $items, 'args' => $args]);
    }

    public static function resolveTagsString(array $video, array $args)
    {
        $items = $video['tags'];
        $args += ['separator' => ', '];

        return app(View::class)->render(Path::get('./templates/list'), ['items' => $items, 'args' => $args]);
    }

    public static function resolveThumbnail(array $video): string
    {
        $id = $video['resource_key'] ?? '';
        $url = Arr::get($video, 'pictures.base_link');

        if (!$url || !$id) {
            return '';
        }

        return Util\File::cacheMedia($url, "vimeo-media-$id");
    }

    public static function resolveIframe(array $video, array $args)
    {
        $args = array_filter($args);

        $id = self::resolveId($video);
        $width = $args['width'] ?? self::DEFAULT_WIDTH;
        $height = $args['height'] ?? self::DEFAULT_HEIGHT;
        $start = $args['start_at'] ?? 0;

        unset($args['start_at']);

        foreach ($args as $key => $val) {
            if (Str::startsWith($key, 'hide_')) {
                unset($args[$key]);

                if ($val) {
                    $key = str_replace('hide_', '', $key);
                    $args[$key] = 0;
                }
            }
        }

        $url = Url::to("https://player.vimeo.com/video/{$id}", $args);

        if (!empty($start)) {
            $url = "{$url}#t={$start}s";
        }

        return sprintf('<iframe src="%s" width="%s" height="%s" frameborder="0"></iframe>', $url, $width, $height);
    }
}
