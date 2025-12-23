<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleBusinessProfileReviewType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileReview';
    public const LABEL = 'Review';

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
                'original_comment' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Original Comment',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveOriginalComment',
                        ],
                    ],
                ],
                'translated_comment' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Translated Comment',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveTranslatedComment',
                        ],
                    ],
                ],
                'starRating' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Rating',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveStarRating',
                        ],
                    ],
                ],
                'createTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'updateTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Updated At',
                        'filters' => ['date'],
                    ],
                ],
                'reviewReply' => [
                    'type' => GoogleBusinessProfileReviewReplyType::NAME,
                    'metadata' => [
                        'label' => 'Reply',
                    ],
                ],
                'reviewer' => [
                    'type' => GoogleBusinessProfileReviewerType::NAME,
                    'metadata' => [
                        'label' => 'Reviewer',
                    ],
                ],
                // 'reviewId' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Review ID',
                //     ],
                // ],
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],
        ];
    }

    public static function resolveStarRating(array $review): ?int
    {
        switch ($review['starRating']) {
            case 'ONE':
                return 1;
            case 'TWO':
                return 2;
            case 'THREE':
                return 3;
            case 'FOUR':
                return 4;
            case 'FIVE':
                return 5;
            case 'STAR_RATING_UNSPECIFIED':
            default:
                return null;
        }
    }

    public static function resolveOriginalComment(array $review): ?string
    {
        return self::parseComment($review['comment'] ?? '')['original'] ?? null;
    }

    public static function resolveTranslatedComment($review): ?string
    {
        return self::parseComment($review['comment'] ?? '')['translated'] ?? null;
    }

    public static function parseComment($comment): array
    {
        $result['original'] = $result['translated'] = $comment;

        if (preg_match('/^([\W\w\s]*)\(Original\)([\W\w\s]*)$/', $comment, $matches)) {
            $result['original'] = $matches[2];
            $result['translated'] = $matches[1];
        } elseif (preg_match('/^([\W\w\s]*)\(Translated by Google\)([\W\w\s]*)$/', $comment, $matches)) {
            $result['original'] = $matches[1];
            $result['translated'] = $matches[2];
        }

        array_walk($result, function (&$v) {
            $v = trim(str_replace(['(Translated by Google)', '(Original)'], '', $v));
        });

        return $result;
    }
}
