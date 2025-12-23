<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky\Type;

use YOOtheme\Url;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class BlueskyPostImageType extends GenericType
{
    public const NAME = 'BlueskyPostImage';
    public const LABEL = 'Post Image';

    public function config(): array
    {
        return [
            'fields' => [
                'thumb' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Thumbnail URL',
                        'description' => 'URL of the thumbnail image',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnail',
                    ],
                ],
                'fullsize' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Fullsize URL',
                        'description' => 'URL of the full-size image',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveFullsize',
                    ],
                ],
                'alt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Alt Text',
                        'description' => 'Alternative text for the image',
                        'filters' => ['limit'],
                    ],
                ],
                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height',
                        'description' => 'Height of the image',
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Width',
                        'description' => 'Width of the image',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveThumbnail(array $image): string
    {
        $url = $image['thumb'] ?? '';
        $id = $image['id'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "bluesky-post-thumbnail-$id");
        }

        return '';
    }

    public static function resolveFullsize(array $image): string
    {
        $url = $image['fullsize'] ?? '';
        $id = $image['id'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "bluesky-post-fullsize-$id");
        }

        return '';
    }

}
