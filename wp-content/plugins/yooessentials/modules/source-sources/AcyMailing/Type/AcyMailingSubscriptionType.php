<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\AcyMailing\Type;

class AcyMailingSubscriptionType
{
    public const NAME = 'acymailingSubscription';
    public const LABEL = 'AcyMailing Subscription';

    public static function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveName',
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'color' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Color',
                    ],
                ],
                'status' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Status',
                    ],
                ],
                'active' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Active',
                    ],
                ],
                'visible' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Visible',
                    ],
                ],
                'subscription_date' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Subscription Date',
                        'filters' => ['date'],
                    ],
                ],
                'unsubscribe_date' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Unsubscription Date',
                        'filters' => ['date'],
                    ],
                ],
                'id' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => self::LABEL,
            ],
        ];
    }

    public static function resolveName(object $obj): string
    {
        $displayName = $obj->display_name ?? '';

        return $displayName ?: $obj->name;
    }
}
