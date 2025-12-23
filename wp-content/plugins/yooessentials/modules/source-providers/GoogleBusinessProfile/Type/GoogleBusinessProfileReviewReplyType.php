<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleBusinessProfileReviewReplyType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileReply';
    public const LABEL = 'Reply';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'comment' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Comment',
                        'filters' => ['limit'],
                    ],
                ],
                'updateTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Updated At',
                        'filters' => ['date'],
                    ],
                ],
            ],
        ];
    }
}
