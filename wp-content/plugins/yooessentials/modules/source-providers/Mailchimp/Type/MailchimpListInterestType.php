<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class MailchimpListInterestType extends GenericType
{
    public const NAME = 'MailchimpListInterest';
    public const LABEL = 'Audience Interest';

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
                'subscriber_count' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Total associated subscribers',
                    ],
                ],
                'id' => [
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
