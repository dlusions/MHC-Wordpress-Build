<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\Provider\Bluesky\BlueskyFacetsProcessor;

class BlueskyPostType extends GenericType
{
    public const NAME = 'BlueskyPost';
    public const LABEL = 'Post';

    public function config(): array
    {
        return [
            'fields' => [
                'cid' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'CID',
                        'description' => 'Unique identifier of the content',
                    ],
                ],
                'uri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URI',
                        'description' => 'Uniform Resource Identifier of the post',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveUri',
                    ],
                ],
                'author' => [
                    'type' => BlueskyProfileType::NAME,
                    'metadata' => [
                        'label' => 'Author',
                        'description' => 'Author of the post',
                    ],
                ],
                'text' => [
                    'type' => 'String',
                    'args' => [
                        'richtext' => [
                            'type' => 'Boolean',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Text',
                        'description' => 'Text of the post',
                        'filters' => ['limit'],
                        'arguments' => [
                            'richtext' => [
                                'label' => 'RichText',
                                'text' => 'Process Bluesky RichText',
                                'type' => 'checkbox',
                                'default' => true
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveText',
                    ],
                ],
                'mediaType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Media Type',
                        'description' => 'If present, the type of media in the post',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMediaType',
                    ],
                ],
                'images' => [
                    'type' => [
                        'listOf' => BlueskyPostImageType::NAME,
                    ],
                    'metadata' => [
                        'label' => 'Images',
                        'description' => 'Images of the post',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveImages',
                    ],
                ],
                'imagesCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Images Count',
                        'description' => 'Count images of the post',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveImagesCount',
                    ],
                ],
                'video' => [
                    'type' => BlueskyPostVideoType::NAME,
                    'metadata' => [
                        'label' => 'Videos',
                        'description' => 'Videos of the post',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveVideo',
                    ],
                ],
                'gif' => [
                    'type' => BlueskyPostGifType::NAME,
                    'metadata' => [
                        'label' => 'GIF',
                        'description' => 'GIF of the post',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveGif',
                    ],
                ],
                'replyCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Reply Count',
                        'description' => 'Number of replies to the post',
                    ],
                ],
                'repostCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Repost Count',
                        'description' => 'Number of reposts of the post',
                    ],
                ],
                'likeCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Like Count',
                        'description' => 'Number of likes on the post',
                    ],
                ],
                'quoteCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Quote Count',
                        'description' => 'Number of quotes of the post',
                    ],
                ],
                'indexedAt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Indexed At',
                        'description' => 'Timestamp when the post was indexed',
                        'filters' => ['date'],
                    ],
                ],
                // 'labels' => [
                //     'type' => [
                //         'listOf' => 'String'
                //     ],
                //     'metadata' => [
                //         'label' => 'Labels',
                //         'description' => 'Labels associated with the post',
                //     ],
                //     'extensions' => [
                //         'call' => __CLASS__ . '::resolveLabels',
                //     ],
                // ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveUri(array $post): string
    {
        $uri = $post['uri'] ?? '';

        if (strpos($uri, 'at://') === 0) {
            $path = substr($uri, 5);

            $parts = explode('/', $path);
            if (count($parts) >= 3) {
                $did = $parts[0];
                $rkey = end($parts);

                return "https://bsky.app/profile/{$did}/post/{$rkey}";
            }
        }

        return $uri;
    }

    public static function resolveText(array $post, array $args): string
    {
        $text = $post['record']['text'] ?? '';

        if ($args['richtext'] ?? false) {
            $facets = $post['record']['facets'] ?? [];

            return BlueskyFacetsProcessor::process($text, $facets);
        }

        return $text;
    }

    public static function resolveMediaType(array $post): string
    {
        if (!isset($post['embed'])) {
            return '';
        }

        return $post['embed']['$type'] ?? '';
    }

    public static function resolveImages(array $post): array
    {
        if (isset($post['embed']) && $post['embed']['$type'] == 'app.bsky.embed.images#view') {
            $images = [];
            foreach ($post['embed']['images'] as $key => $image) {
                $image['id'] = $post['record']['embed']['images'][$key]['image']['ref']['$link'] ?? '';
                $images[$key] = $image;
            }

            return $images;
        }

        return [];
    }

    public static function resolveGif(array $post): array
    {
        if (isset($post['embed']) && $post['embed']['$type'] == 'app.bsky.embed.external#view') {
            $image = $post['embed']['external'] ?? [];
            $image['id'] = $post['cid'] ?? '';

            return $image;
        }

        return [];
    }

    public static function resolveImagesCount(array $post): int
    {
        if (isset($post['embed']) && $post['embed']['$type'] == 'app.bsky.embed.images#view') {
            return count($post['embed']['images']);
        }

        return 0;
    }

    public static function resolveVideo(array $post): array
    {
        if (isset($post['embed']) && $post['embed']['$type'] == 'app.bsky.embed.video#view') {
            return $post['embed'] ?? [];
        }

        return [];
    }

    public static function resolveLabels(array $post): array
    {
        if (count($post['labels'])) {
            return array_map(fn ($label) => $label['val'], $post['labels']);
        }

        return [];
    }
}
