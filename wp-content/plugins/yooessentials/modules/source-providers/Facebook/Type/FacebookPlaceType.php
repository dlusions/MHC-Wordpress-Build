<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

// https://developers.facebook.com/docs/graph-api/reference/page
class FacebookPlaceType extends GenericType
{
    public const NAME = 'FacebookPlace';
    public const LABEL = 'Place';

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                        'filters' => ['limit'],
                    ],
                ],
                'city' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'City',
                        'filters' => ['limit'],
                    ],
                ],
                'country' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Country',
                        'filters' => ['limit'],
                    ],
                ],
                'street' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Street',
                        'filters' => ['limit'],
                    ],
                ],
                'zip' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Zip Code',
                        'filters' => ['limit'],
                    ],
                ],
                'latitude' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Latitude'
                    ],
                ],
                'longitude' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Longitude'
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolve($place): array
    {
        return $place['location'] + ['name' => $place['name']];
    }
}
