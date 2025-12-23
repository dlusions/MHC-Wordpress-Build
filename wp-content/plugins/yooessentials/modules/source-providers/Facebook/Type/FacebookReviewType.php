<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class FacebookReviewType extends GenericType
{
    public const NAME = 'FacebookReview';
    public const LABEL = 'Review';

    public function config(): array
    {
        return [
            'fields' => [
                'review_text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Text',
                        'filters' => ['limit'],
                    ],
                ],
                'rating' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Rating',
                    ],
                ],
                'recommendation_type' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                    ],
                ],
                'created_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'has_rating' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Has Rating',
                    ],
                ],
                'has_review' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Has Review',
                    ]
                ],
                'reviewer' => [
                    'type' => FacebookUserType::NAME,
                    'metadata' => [
                        'label' => 'Reviewer',
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
