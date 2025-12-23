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

class TwitterMediaVideoType extends GenericType
{
    public const NAME = 'TwitterMediaVideo';
    public const LABEL = 'Video';

    public function config(): array
    {
        return [
            'fields' => [
                'preview_image_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Preview URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::preview',
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
                'duration_ms' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Duration (ms)',
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

    public static function preview($media): string
    {
        $url = $media['preview_image_url'] ?? '';
        $cacheKey = 'twitter-tweet-media-' . sha1($url);

        return Util\File::cacheMedia($url, $cacheKey);
    }
}
