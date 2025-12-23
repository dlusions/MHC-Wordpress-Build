<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Vimeo\Type;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class VimeoTagType extends GenericType
{
    public const NAME = 'VimeoTag';
    public const LABEL = 'Tag';

    public const FIELDS = ['name', 'link', 'resource_key', 'metadata.connections.videos.total'];

    public function config(): array
    {
        return [
            'fields' => [
                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                ],
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                ],
                'videos_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Videos',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolveProp',
                            'args' => [
                                'path' => 'metadata.connections.videos.total',
                            ],
                        ],
                    ],
                ],
                'resource_key' => [
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

    public static function resolveProp(array $video, array $args)
    {
        return Arr::get($video, $args['path']);
    }
}
