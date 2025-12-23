<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GooglePhotos\Type;

use YOOtheme\GraphQL\Type\Definition\ResolveInfo;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GooglePhotosMediaMetadataType extends GenericType
{
    public const NAME = 'GooglePhotosMediaMetadata';
    public const LABEL = 'Metadata';

    public function config(): array
    {
        return [
            'fields' => [
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
                'creationTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Creation Time',
                        'filters' => ['date'],
                    ],
                ],
                'cameraMake' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Camera Brand',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveField',
                    ],
                ],
                'cameraModel' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Camera Model',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveField',
                    ],
                ],
                'isoEquivalent' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Camera ISO',
                        'group' => 'Photo',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveField',
                    ],
                ],
                'focalLength' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Focal Length',
                        'group' => 'Photo',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveField',
                    ],
                ],
                'apertureFNumber' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Aperture F',
                        'group' => 'Photo',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveField',
                    ],
                ],
                'exposureTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Exposure Time (s)',
                        'group' => 'Photo',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveField',
                    ],
                ],
                'fps' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Frame Rate',
                        'group' => 'Video',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveField',
                    ],
                ],
            ],
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveField(
        array $media,
        $args = [],
        $context = '',
        ?ResolveInfo $info = null
    ): string {
        $data = $metadata['photo'] ?? $metadata['video'] ?? [];

        return $data[$info->fieldName] ?? '';
    }
}
