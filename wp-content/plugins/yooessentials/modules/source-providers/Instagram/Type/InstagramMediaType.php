<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Api\Instagram\InstagramMediaTypes;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class InstagramMediaType extends GenericType
{
    public const NAME = 'InstagramMedia';
    public const LABEL = 'Media';

    public function config(): array
    {
        return [
            'fields' => [
                'media_type' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                    ],
                ],
                'media_url' => [
                    // keeping media_url name for historic reason
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Media Preview URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::mediaPreviewUrl',
                    ],
                ],
                'media_url_raw' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Media URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::mediaUrl',
                    ],
                ],
                'permalink' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Permalink',
                    ],
                ],
                'caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Caption',
                        'filters' => ['limit'],
                    ],
                ],
                'hashtags_list' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Hashtags',
                        'arguments' => [
                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'Set the separator between hashtags.',
                                'default' => ', ',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tagString',
                    ],
                ],
                'timestamp' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'username' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created By',
                    ],
                ],
                'comments_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Comments',
                    ],
                ],
                'like_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Likes',
                    ],
                ],
                'hashtags' => [
                    'type' => [
                        'listOf' => 'String',
                    ],
                    'metadata' => [
                        'label' => 'Hashtags',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tags',
                    ],
                ],
                'children' => [
                    'type' => [
                        'listOf' => InstagramAlbumMediaType::NAME,
                    ],
                    'metadata' => [
                        'label' => 'Children Media',
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

    public static function mediaUrl($media)
    {
        return self::cacheMedia($media, 'media_url');
    }

    public static function mediaPreviewUrl($media)
    {
        $type = $media['media_type'] ?? '';

        if ($type === InstagramMediaTypes::TYPE_VIDEO) {
            return self::cacheMedia($media, 'thumbnail_url');
        }

        if ($type === InstagramMediaTypes::TYPE_ALBUM && isset($media['children']['data'][0])) {
            return self::mediaUrl($media['children']['data'][0]);
        }

        return self::mediaUrl($media);
    }

    public static function tags($media)
    {
        preg_match_all('/#([^ ]+)/', $media['caption'] ?? '', $matches);

        return $matches[0] ?: [];
    }

    public static function tagString($media, array $args)
    {
        $tags = static::tags($media);
        $args += ['separator' => ', '];

        return implode($args['separator'], $tags);
    }

    protected static function cacheMedia(array $media, string $key = 'media_url')
    {
        $url = $media[$key] ?? '';

        if (!$url) {
            return '';
        }

        $id = $media['id'] ?? '';
        $type = Str::lower($media['media_type'] ?? 'image');

        if (Str::startsWith($type, 'carousel')) {
            $type = 'album';
        }

        $cacheKey = "ig-media-$type-$id";

        return Util\File::cacheMedia($url, $cacheKey);
    }
}
