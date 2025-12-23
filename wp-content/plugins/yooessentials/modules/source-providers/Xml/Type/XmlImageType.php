<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class XmlImageType extends GenericType
{
    public const NAME = 'XMLImage';
    public const LABEL = 'Image';

    public function config(): array
    {
        return [
            'fields' => [
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                        'fields' => [],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::url',
                    ],
                ],
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'fields' => [],
                    ],
                ],
                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link',
                        'fields' => [],
                    ],
                ],
            ],
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function url($image): string
    {
        $url = $image['url'] ?? '';
        $cacheKey = 'xml-image-' . sha1($url);

        return Util\File::cacheMedia($url, $cacheKey);
    }
}
