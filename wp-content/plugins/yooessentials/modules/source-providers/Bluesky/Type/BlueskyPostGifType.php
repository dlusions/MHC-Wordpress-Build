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

class BlueskyPostGifType extends GenericType
{
    public const NAME = 'BlueskyPostGif';
    public const LABEL = 'Post GIF';

    public function config(): array
    {
        return [
            'fields' => [
                'uri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URI',
                        'description' => 'URL of the GIF',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveUri',
                    ],
                ],
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'description' => 'Title of the GIF',
                        'filters' => ['limit'],
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'description' => 'Description of the GIF',
                        'filters' => ['limit'],
                    ],
                ],
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
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveThumbnail(array $gif): string
    {
        $url = $gif['thumb'] ?? '';
        $id = $gif['id'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "bluesky-post-thumbnail-$id");
        }

        return '';
    }

    public static function resolveUri(array $gif): string
    {
        $url = $gif['uri'] ?? '';
        $id = $gif['id'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "bluesky-post-fullsize-$id");
        }

        return '';
    }

}
