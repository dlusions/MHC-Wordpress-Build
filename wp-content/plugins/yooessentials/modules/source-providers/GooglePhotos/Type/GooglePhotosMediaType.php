<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GooglePhotos\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class GooglePhotosMediaType extends GenericType
{
    public const NAME = 'GooglePhotosMedia';
    public const LABEL = 'Media';

    public function config(): array
    {
        return [
            'fields' => [
                'url' => [
                    'type' => 'String',
                    'args' => [
                        'width' => [
                            'type' => 'Int',
                        ],
                        'height' => [
                            'type' => 'Int',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'URL',
                        'arguments' => [
                            'width' => [
                                'label' => 'Width',
                                'type' => 'Number',
                                'default' => 1200,
                                'args' => [
                                    'min' => 10,
                                    'min' => 10000,
                                ],
                                'description' => 'The maximum width to scale the media to.',
                            ],
                            'height' => [
                                'label' => 'Height',
                                'type' => 'Number',
                                'default' => 1200,
                                'args' => [
                                    'min' => 10,
                                    'min' => 10000,
                                ],
                                'description' => 'The maximum height to scale the media to.',
                            ],
                        ]
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveUrl',
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'filename' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Filename'
                    ],
                ],
                'mimeType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'MIME Type',
                    ],
                ],
                'mediaMetadata' => [
                    'type' => GooglePhotosMediaMetadataType::NAME,
                    'metadata' => [
                        'label' => 'Metadata',
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

    public static function resolveUrl(array $media, array $args): string
    {
        $baseUrl = $media['baseUrl'] ?? '';
        $mimeType = $media['mimeType'] ?? '';
        $maxWidth = $args['width'] ?? 10000;
        $maxHeight = $args['height'] ?? 10000;

        $url = "$baseUrl=w{$maxWidth}-h{$maxHeight}";
        $id = sha1($url);

        return Util\File::cacheMedia($url, "google-photos-media-$id", $mimeType);
    }
}
