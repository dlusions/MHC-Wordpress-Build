<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\AcyMailing\Type;

class AcyMailingSubscriberType
{
    public const NAME = 'acymailingSubscriber';
    public const LABEL = 'AcyMailing Subscriber';

    public static function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                ],
                'email' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Email',
                    ],
                ],
                'creation_date' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Creation Date',
                        'filters' => ['date'],
                    ],
                ],
                'confirmation_date' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Confirmation Date',
                        'filters' => ['date'],
                    ],
                ],
                'confirmation_ip' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Confirmation IP',
                    ],
                ],
                'subscriptionsString' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                        'map' => [
                            'type' => 'String',
                        ]
                    ],
                    'metadata' => [
                        'label' => 'Subscriptions',
                        'arguments' => [
                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'Set the separator between tags.',
                                'default' => ', ',
                            ],
                            'map' => [
                                'label' => 'Map',
                                'type' => 'select',
                                'description' => 'Chose a subscription value to map.',
                                'default' => 'name',
                                'options' => [
                                    'Name' => 'name',
                                    'ID' => 'id',
                                ]
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::subscriptionsString',
                    ],
                ],
                'active' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Active',
                    ],
                ],
                'confirmed' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Confirmed',
                    ],
                ],
                'tracking' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Tracking',
                    ],
                ],
                'id' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
                'subscriptions' => [
                    'type' => [
                        'listOf' => AcyMailingSubscriptionType::NAME,
                    ],
                    'metadata' => [
                        'label' => 'Subscriptions',
                    ],
                ],
                // 'cms_id' => [
                //     'type' => 'User',
                //     'metadata' => [
                //         'label' => 'User',
                //     ],
                // ],
            ],

            'metadata' => [
                'type' => true,
                'label' => self::LABEL,
            ],
        ];
    }

    public static function subscriptionsString(array $subscriber, array $args): string
    {
        $separator = $args['separator'] ?? ', ';
        $map = $args['map'] ?? 'name';

        return implode($separator, array_map(
            fn ($subscription) => $subscription->$map,
            $subscriber['subscriptions'] ?? []
        ));
    }
}
