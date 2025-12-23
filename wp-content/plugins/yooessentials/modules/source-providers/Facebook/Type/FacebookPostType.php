<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use function YOOtheme\app;
use YOOtheme\Path;
use YOOtheme\Str;
use YOOtheme\View;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class FacebookPostType extends GenericType
{
    public const NAME = 'FacebookPost';
    public const LABEL = 'Post';

    public function config(): array
    {
        return [
            'fields' => [
                'permalink_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Permalink',
                    ],
                ],
                'full_picture' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Picture URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::fullPicture',
                    ],
                ],
                'message' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Message',
                        'filters' => ['limit'],
                    ],
                ],
                'from' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'From',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveFrom',
                    ],
                ],
                'tags_string' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                        'show_link' => [
                            'type' => 'Boolean',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Tags',
                        'arguments' => [
                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'Set the separator between.',
                                'default' => ', ',
                            ],
                            'show_link' => [
                                'label' => 'Link',
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => 'Show link',
                            ],
                            'link_style' => [
                                'label' => 'Link Style',
                                'description' => 'Set the link style.',
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'Default' => '',
                                    'Muted' => 'link-muted',
                                    'Text' => 'link-text',
                                    'Heading' => 'link-heading',
                                    'Reset' => 'link-reset',
                                ],
                                'enable' => 'arguments.show_link',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveTagsString',
                    ],
                ],
                'created_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'updated_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Updated At',
                        'filters' => ['date'],
                    ],
                ],
                'is_published' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Published',
                    ],
                ],
                'is_expired' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Expired',
                    ],
                ],
                'is_hidden' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Hidden',
                    ],
                ],
                'is_popular' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Popular',
                    ],
                ],
                // 'action_like' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Action Like URL',
                //     ],
                //     'extensions' => [
                //         'call' => [
                //             'func' => __CLASS__ . '::resolveActionUrl',
                //             'args' => [
                //                 'action' => 'like',
                //             ]
                //         ]
                //     ],
                // ],
                // 'action_comment' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Action Comment URL',
                //     ],
                //     'extensions' => [
                //         'call' => [
                //             'func' => __CLASS__ . '::resolveActionUrl',
                //             'args' => [
                //                 'action' => 'comment',
                //             ]
                //         ]
                //     ],
                // ],
                // 'action_share' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Action Share URL',
                //     ],
                //     'extensions' => [
                //         'call' => [
                //             'func' => __CLASS__ . '::resolveActionUrl',
                //             'args' => [
                //                 'action' => 'share',
                //             ]
                //         ]
                //     ],
                // ],
                'total_shares' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Shares',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::totalShares',
                    ],
                ],
                'total_likes' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Likes',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::totalLikes',
                    ],
                ],
                'total_comments' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Comments',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::totalComments',
                    ],
                ],
                'total_reactions' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Reactions',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::totalReactions',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
                'parent_id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Parent ID',
                    ],
                ],
                'attachment' => [
                    'type' => FacebookStoryAttachment::NAME,
                    'metadata' => [
                        'label' => 'Attachment',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveAttachment',
                    ],
                ],
                'attachments' => [
                    'type' => [
                        'listOf' => FacebookStoryAttachment::NAME,
                    ],
                    'metadata' => [
                        'label' => 'Attachments',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveAttachments',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    // public static function resolveActionUrl($post, $args): string
    // {
    //     $actions = $post['actions'] ?? [];
    //     $urls = array_combine(array_column($actions, 'name'), array_column($actions, 'link'));

    //     return $urls[Str::upperFirst($args['action'])];
    // }

    public static function resolveFrom($post): string
    {
        return $post['from']['name'] ?? '';
    }

    public static function totalLikes($post): int
    {
        return $post['likes']['summary']['total_count'] ?? 0;
    }

    public static function totalComments($post): int
    {
        return $post['comments']['summary']['total_count'] ?? 0;
    }

    public static function totalReactions($post): int
    {
        return $post['reactions']['summary']['total_count'] ?? 0;
    }

    public static function totalShares($post): int
    {
        return $post['shares']['count'] ?? 0;
    }

    public static function fullPicture($post)
    {
        $id = $post['id'] ?? '';
        $url = $post['full_picture'] ?? '';

        return Util\File::cacheMedia($url, "facebook-post-$id");
    }

    public static function resolveTagsString(array $post, array $args)
    {
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];
        $items = array_filter($post['message_tags'] ?? [], fn ($tag) => Str::startsWith($tag['name'], '#'));

        return app(View::class)->render(Path::get('../templates/list'), ['items' => $items, 'args' => $args]);
    }

    public static function resolveAttachment($post): array
    {
        return $post['attachments']['data'][0] ?? [];
    }

    public static function resolveAttachments($post): array
    {
        return $post['attachments']['data'] ?? [];
    }
}
