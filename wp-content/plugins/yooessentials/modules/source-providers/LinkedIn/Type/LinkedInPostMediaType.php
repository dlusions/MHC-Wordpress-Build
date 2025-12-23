<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn\Type;

use YOOtheme\Url;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class LinkedInPostMediaType extends GenericType
{
    public const NAME = 'LinkedinPostMedia';
    public const LABEL = 'Media';

    public function config(): array
    {
        return [
            'fields' => [
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                        'description' => 'Unique identifier of the content',
                    ],
                ],
                'altText' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Alt Text',
                        'description' => 'Alternative Text for the media content',
                        'filters' => ['limit'],
                    ],
                ],
                'downloadUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                        'description' => 'URL of the media content',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveUrl',
                    ],
                ],
                'mediaType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                        'description' => 'Type of the media content, either image or video',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveUrl($media): string
    {
        $id = str_replace('urn:li:image:', '', $media['id'] ?? '');
        $id = str_replace('urn:li:video:', '', $media['id'] ?? '');

        $url = $media['downloadUrl'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "linkedin-post-media-$id");
        }

        return '';
    }
}
