<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Airtable\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class AirtableAttachmentType extends GenericType
{
    public const NAME = 'AirtableAttachment';
    public const LABEL = 'Attachment';

    public function config(): array
    {
        return [
            'fields' => [
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                ],
                'filename' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Filename',
                    ],
                ],
                'type' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                    ],
                ],
                'size' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Size',
                    ],
                ],
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
                'thumbnail' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Thumbnail',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnail',
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

    public static function resolveThumbnail($attachment)
    {
        $id = $attachment['id'] ?? '';
        $url = $attachment['thumbnails']['full']['url'] ?? '';

        if (!$id || !$url) {
            return null;
        }

        return Util\File::cacheMedia($url, "airtable-attachment-$id-thumbnail");
    }
}
