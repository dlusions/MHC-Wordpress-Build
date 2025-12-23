<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class FacebookStoryAttachment extends GenericType
{
    public const NAME = 'FacebookStoryAttachment';
    public const LABEL = 'Attachment';

    public function config(): array
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                    ],
                ],
                'type' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                    ],
                ],
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                ],
                'media_src' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Media Src',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMediaSrc',
                    ],
                ],
                'media_image_src' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Image Src',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMediaImageSrc',
                    ],
                ],
                'media_image_width' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Image Width',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMediaImageWidth',
                    ],
                ],
                'media_image_height' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Image Height',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMediaImageHeight',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveMediaSrc($post): string
    {
        $type = $post['type'] ?? '';

        if ($type === 'video_inline') {
            return $post['media']['source'] ?? '';
        }

        return $post['media']['image']['src'] ?? '';
    }

    public static function resolveMediaImageSrc($post): string
    {
        return $post['media']['image']['src'] ?? '';
    }

    public static function resolveMediaImageWidth($post): string
    {
        return $post['media']['image']['width'] ?? '';
    }

    public static function resolveMediaImageHeight($post): string
    {
        return $post['media']['image']['height'] ?? '';
    }
}
