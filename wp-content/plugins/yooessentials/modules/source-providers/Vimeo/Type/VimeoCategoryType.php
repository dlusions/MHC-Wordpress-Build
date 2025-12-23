<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Vimeo\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class VimeoCategoryType extends GenericType
{
    public const NAME = 'VimeoCategory';
    public const LABEL = 'Category';

    public const FIELDS = ['name', 'link', 'top_level', 'parent', 'resource_key'];

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                ],
                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Link',
                    ],
                ],
                'top_level' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Top Level',
                    ],
                ],
                'parent' => [
                    'type' => 'VimeoCategory',
                    'metadata' => [
                        'label' => 'Parent Category',
                    ],
                ],
                'subcategories' => [
                    'type' => [
                        'listOf' => 'VimeoCategory',
                    ],
                    'metadata' => [
                        'label' => 'Subcategories',
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
