<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Twitter\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class TwitterMediaPhotoType extends GenericType
{
    public const NAME = 'TwitterMediaPhoto';
    public const LABEL = 'Image';

    public function config(): array
    {
        return [
            'fields' => [
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::url',
                    ],
                ],
                'alt_text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ALT Text',
                    ],
                ],
                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height',
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Width',
                    ],
                ],
                'media_key' => [
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

    public static function url($media): string
    {
        $url = $media['url'] ?? '';
        $cacheKey = 'twitter-tweet-media-' . sha1($url);

        return Util\File::cacheMedia($url, $cacheKey);
    }
}
