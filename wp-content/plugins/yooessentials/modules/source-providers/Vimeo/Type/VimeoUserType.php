<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Vimeo\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class VimeoUserType extends GenericType
{
    public const NAME = 'VimeoUser';
    public const LABEL = 'User';

    public const FIELDS = [
        'name',
        'bio',
        'short_bio',
        'gender',
        'link',
        'location',
        'is_expert',
        'verified',
        'resource_key',
    ];

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
                'gender' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Gender',
                    ],
                ],
                'bio' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Bio',
                        'filters' => ['limit'],
                    ],
                ],
                'short_bio' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Short Bio',
                        'filters' => ['limit'],
                    ],
                ],
                'location' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Location',
                    ],
                ],
                'is_expert' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Expert',
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
}
