<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleBusinessProfileMediaLocationAssociationType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileMediaLocationAssociation';
    public const LABEL = 'Media Association';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'category' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Category',
                    ],
                ],
                'priceListItemId' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Price List Item ID',
                    ],
                ],
            ],
        ];
    }
}
