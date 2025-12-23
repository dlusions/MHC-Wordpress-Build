<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class GoogleBusinessProfileMediaType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileMedia';
    public const LABEL = 'Media';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'mediaFormat' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Format',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMediaFormat',
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Width (px)',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveWidth',
                    ],
                ],
                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height (px)',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveHeight',
                    ],
                ],
                'thumbnailUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Thumbnail URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnailUrl',
                    ],
                ],
                'sourceUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Source URL',
                    ],
                ],
                'googleUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Google URL',
                    ],
                ],
                'createTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'viewCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Views',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveViewCount',
                    ],
                ],
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
                'attribution' => [
                    'type' => GoogleBusinessProfileMediaAttributionType::NAME,
                    'metadata' => [
                        'label' => 'Attribution',
                    ],
                ],
                'locationAssociation' => [
                    'type' => GoogleBusinessProfileMediaLocationAssociationType::NAME,
                    'metadata' => [
                        'label' => 'Location Association',
                    ],
                ],
            ],
        ];
    }

    public static function resolveMediaFormat(array $media): ?string
    {
        return $media['mediaFormat'] ?? null;
    }

    public static function resolveWidth(array $media): ?int
    {
        return $media['dimensions']['widthPixels'] ?? null;
    }

    public static function resolveHeight(array $media): ?int
    {
        return $media['dimensions']['heightPixels'] ?? null;
    }

    public static function resolveViewCount(array $media): ?int
    {
        return $media['insights']['viewCount'] ?? null;
    }

    public static function resolveThumbnailUrl(array $media): ?string
    {
        $name = $media['name'] ?? '';
        $url = $media['thumbnailUrl'] ?? '';

        return Util\File::cacheMedia($url, "gbp-media-$name");
    }
}
