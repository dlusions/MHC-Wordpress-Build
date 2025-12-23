<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleBusinessProfilePostalAddressType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileStoreAddress';
    public const LABEL = 'Postal Address';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'organization' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Organization',
                    ],
                ],
                'address' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Address',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveAddress',
                    ],
                ],
                'fullAddress' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Full Address',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveFullAddress',
                    ],
                ],
                'locality' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Locality',
                    ],
                ],
                'sublocality' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Sublocality',
                    ],
                ],
                'administrativeArea' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Administrative Area',
                    ],
                ],
                'regionCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Region Code',
                    ],
                ],
                'sortingCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Sorting Code',
                    ],
                ],
                'postalCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Postal Code',
                    ],
                ],
                'languageCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Language Code',
                    ],
                ],
                // 'addressLines' => [
                //     'type' => ['listOf' => 'String'],
                //     'metadata' => [
                //         'label' => 'Address Lines',
                //     ]
                // ],
                // 'recipients' => [
                //     'type' => ['listOf' => 'String'],
                //     'metadata' => [
                //         'label' => 'Recipients',
                //     ]
                // ],
            ],
        ];
    }

    public static function resolveAddress(array $address): string
    {
        return implode(' ', $address['addressLines'] ?? []);
    }

    public static function resolveFullAddress(array $address): string
    {
        return implode(
            ', ',
            array_filter([
                self::resolveAddress($address),
                $address['postalCode'] ?? '',
                $address['locality'] ?? '',
                $address['administrativeArea'] ?? '',
            ])
        );
    }
}
