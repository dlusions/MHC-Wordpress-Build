<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use function YOOtheme\app;
use YOOtheme\Path;
use YOOtheme\Str;
use YOOtheme\View;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class FacebookPhotoType extends GenericType
{
    public const NAME = 'FacebookPhoto';
    public const LABEL = 'Photo';

    public function config(): array
    {
        return [
            'fields' => [
                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link',
                    ],
                ],
                'image_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Picture URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::fullPicture',
                    ],
                ],
                'caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Caption',
                        'filters' => ['limit'],
                    ],
                ],
                'alt_text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Alt Text',
                        'filters' => ['limit'],
                    ],
                ],
                'alt_text_custom' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Alt Text Custom',
                        'filters' => ['limit'],
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'From',
                    ]
                ],

                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height',
                    ]
                ],

                'album_name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Album Name',
                        'filters' => ['limit'],
                    ],
                ],

                'album_id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Album ID',
                    ],
                ],

                'place' => [
                    'type' => FacebookPlaceType::NAME,
                    'metadata' => [
                        'label' => 'Place'
                    ],
                ],

                'total_shares' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Shares',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::totalShares',
                    ],
                ],
                'total_likes' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Likes',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::totalLikes',
                    ],
                ],
                'total_comments' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Comments',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::totalComments',
                    ],
                ],
                'tags_string' => [
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
                        'label' => 'Tags',
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
                        'call' => __CLASS__ . '::resolveTagsString',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function totalLikes($post): int
    {
        return $post['likes']['summary']['total_count'] ?? 0;
    }

    public static function totalComments($post): int
    {
        return $post['comments']['summary']['total_count'] ?? 0;
    }

    public static function totalShares($post): int
    {
        return $post['shares']['count'] ?? 0;
    }

    public static function fullPicture($photo)
    {
        $id = $photo['id'] ?? '';
        $images = $photo['images'] ?? [];

        // Sort by width
        usort($images, function ($imageA, $imageB) {
            if (($imageA['width'] ?? 0) === ($imageB['width'] ?? 0)) {
                return 0;
            }

            return ($imageA['width']) ?? 0 < ($imageB['width'] ?? 0) ? -1 : 1;
        });

        $biggestImage = array_pop($images);

        return Util\File::cacheMedia($biggestImage['source'], "facebook-photo-$id");
    }

    public static function resolveTagsString(array $post, array $args)
    {
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];
        $items = array_filter($post['name_tags'] ?? [], fn ($tag) => Str::startsWith($tag['name'], '#'));

        return app(View::class)->render(Path::get('../templates/list'), ['items' => $items, 'args' => $args]);
    }
}
