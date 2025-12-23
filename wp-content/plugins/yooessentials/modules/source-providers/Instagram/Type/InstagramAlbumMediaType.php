<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class InstagramAlbumMediaType extends GenericType
{
    public const NAME = 'InstagramAlbumMedia';
    public const LABEL = 'Album Media';

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
                'media_url_raw' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::mediaUrl',
                    ],
                ],
                'media_url' => [
                    // keeping media_url name for historic reason
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Thumbnail URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::mediaThumbnail',
                    ],
                ],
                'permalink' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Permalink',
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
}
