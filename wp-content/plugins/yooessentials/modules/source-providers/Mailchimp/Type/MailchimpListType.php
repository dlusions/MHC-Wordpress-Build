<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class MailchimpListType extends GenericType
{
    public const NAME = 'MailchimpList';
    public const LABEL = 'Audience';

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name'
                    ],
                ],
                'date_created' => [
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
                'subscribe_url_short' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Subscribe Short URL',
                    ],
                ],
                'subscribe_url_long' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Subscribe Long URL',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ]
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }
}
