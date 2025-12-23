<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class MailchimpListMemberType extends GenericType
{
    public const NAME = 'MailchimpListMember';
    public const LABEL = 'Audience Member';

    public function config(): array
    {
        return [
            'fields' => [
                'email_address' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Email',
                    ],
                ],
                // 'email_type' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Email Type',
                //     ],
                // ],
                'full_name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Full Name',
                        'filters' => ['limit'],
                    ],
                ],
                'status' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Status',
                    ],
                ],
                'vip' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'VIP',
                    ],
                ],
                'language' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Language',
                    ],
                ],
                'timestamp_signup' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created',
                        'filters' => ['date'],
                    ],
                ],
                'last_changed' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Changed',
                        'filters' => ['date'],
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
                // 'interests' => [
                //     'type' => ['listOf' => 'String'],
                //     'metadata' => [
                //         'label' => 'Interests',
                //     ],
                //     'extensions' => [
                //         'call' => __CLASS__ . '::resolveInterests',
                //     ],
                // ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveInterests(array $member, array $args): array
    {
        $interests = (array) $member['interests'] ?? [];

        return array_keys(array_filter($interests));

        // return array_map(
        //     fn ($id, $status) => compact('id', 'status'),
        //     array_keys($interests), array_values($interests)
        // );
    }
}
