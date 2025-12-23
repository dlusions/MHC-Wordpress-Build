<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn\Type;

use YOOtheme\GraphQL\Type\Definition\ResolveInfo;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\Provider\LinkedIn\LinkedInHelper;

class LinkedInPostType extends GenericType
{
    public const NAME = 'LinkedinPost';
    public const LABEL = 'Post';

    public function config(): array
    {
        return [
            'fields' => [
                // not supported because of API limitation
                // 'permalink' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Permalink',
                //     ],
                //     'extensions' => [
                //         'call' => __CLASS__ . '::permalink',
                //     ],
                // ],
                'lifecycleState' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'State',
                        'description' => 'The state of the content',
                    ],
                ],
                'visibility' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Visibility',
                        'description' => 'The visibility of the content',
                    ],
                ],
                'commentary' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Commentary',
                        'description' => 'The user generated commentary for the content',
                        'filters' => ['limit'],
                    ],
                ],
                'commentary_html' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Commentary HTML',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::commentary',
                    ],
                ],
                'createdAt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::datetime',
                    ],
                ],
                'publishedAt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Published At',
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::datetime',
                    ],
                ],
                'lastModifiedAt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Modified At',
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::datetime',
                    ],
                ],
                'author' => [
                    'type' => LinkedInOrganizationType::NAME,
                    'metadata' => [
                        'label' => 'Author',
                        'description' => 'The author of the content',
                    ],
                ],
                'contentArticle' => [
                    'type' => LinkedInPostArticleType::NAME,
                    'metadata' => [
                        'label' => 'Article',
                        'description' => 'The posted content of an Article.',
                    ],
                ],
                'contentMedia' => [
                    'type' => LinkedInPostMediaType::NAME,
                    'metadata' => [
                        'label' => 'Media',
                        'description' => 'The posted media.',
                    ],
                ],
                'contentImage' => [
                    'type' => LinkedInPostMediaType::NAME,
                    'metadata' => [
                        'label' => 'Image',
                        'description' => 'The first posted image.',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::image',
                    ],
                ],
                'contentImages' => [
                    'type' => [
                        'listOf' => LinkedInPostMediaType::NAME
                    ],
                    'metadata' => [
                        'label' => 'Images',
                        'description' => 'The posted images.',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                        'description' => 'Unique identifier of the content',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    // public static function permalink(array $post): ?string
    // {
    //     $urn = $post['id'] ?? '';

    //     if (preg_match('/urn:li:share:(\d+)/', $urn, $matches)) {
    //         $id = $matches[1];
    //         return "https://www.linkedin.com/feed/update/$id";
    //     }

    //     return null;
    // }

    public static function datetime(array $post, $args = [], $context = '', ?ResolveInfo $info = null): ?string
    {
        $datetime = $post[$info->fieldName] ?? null;

        if ($datetime) {
            return (int) ($datetime / 1000);
        }

        return null;
    }

    public static function commentary(array $post): ?string
    {
        $commentary = $post['commentary'] ?? null;

        if ($commentary) {
            return LinkedInHelper::parseCommentary($commentary);
        }

        return null;
    }

    public static function image(array $post): ?array
    {
        return $post['contentImages'][0] ?? null;
    }
}
