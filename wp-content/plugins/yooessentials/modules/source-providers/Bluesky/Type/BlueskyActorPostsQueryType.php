<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Bluesky\BlueskySource;

class BlueskyActorPostsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'posts';
    public const LABEL = 'Posts';
    public const DESCRIPTION = 'Posts made by the profile matching search criteria';

    public static function getCacheKey(): string
    {
        return 'bluesky-actor-posts';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => BlueskyPostType::NAME,
                    ],
                    'args' => [
                        'q' => [
                            'type' => 'String',
                        ],
                        'since' => [
                            'type' => 'String',
                        ],
                        'until' => [
                            'type' => 'String',
                        ],
                        'mentions' => [
                            'type' => 'String',
                        ],
                        'author' => [
                            'type' => 'String',
                        ],
                        'lang' => [
                            'type' => 'String',
                        ],
                        'domain' => [
                            'type' => 'String',
                        ],
                        'url' => [
                            'type' => 'String',
                        ],
                        'tag' => [
                            'type' => 'String',
                        ],
                        'sort' => [
                            'type' => 'String',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        // 'cursor' => [
                        //     'type' => 'Int',
                        // ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            'q' => [
                                'label' => 'Query',
                                'description' => 'Syntax, phrase, boolean, and faceting is unspecified, but Lucene query syntax is recommended.',
                                'source' => true,
                                'required' => true,
                            ],
                            'since' => [
                                'label' => 'Since',
                                'type' => 'yooessentials-datetime',
                                'description' => 'Filter results for posts after the indicated datetime (inclusive). Can be a datetime, or just an ISO date <code>YYYY-MM-DD</code>.',
                                'source' => true,
                            ],
                            'until' => [
                                'label' => 'Until',
                                'type' => 'yooessentials-datetime',
                                'description' => 'Filter results for posts before the indicated datetime (not inclusive). Can be a datetime, or just an ISO date <code>YYYY-MM-DD</code>.',
                                'source' => true,
                            ],
                            'mentions' => [
                                'label' => 'Mentions',
                                'description' => 'Filter to posts which mention the given account.',
                                'source' => true,
                            ],
                            // Limitted to Source Actor
                            // 'author' => [
                            //     'label' => 'Author',
                            //     'description' => 'Filter to posts by the given account.',
                            //     'source' => true,
                            // ],
                            'lang' => [
                                'label' => 'Language',
                                'description' => 'Filter to posts in the given language. Expected to be based on post language field, though Bluesky server may override language detection.',
                                'source' => true,
                            ],
                            'domain' => [
                                'label' => 'Domain',
                                'description' => 'Filter to posts with URLs (facet links or embeds) linking to the given domain (hostname). Bluesky server may apply hostname normalization.',
                                'source' => true,
                            ],
                            'url' => [
                                'label' => 'URL',
                                'description' => 'Filter to posts with links (facet links or embeds) pointing to this URL. Bluesky server may apply URL normalization or fuzzy matching.',
                                'source' => true,
                            ],
                            'tag' => [
                                'label' => 'Tag',
                                'description' => 'Filter to posts with the given tag (hashtag), based on rich-text facet or tag field. Do not include the hash (#) prefix. Multiple tags can be specified, with <code>AND</code> matching.',
                                'source' => true,
                            ],
                            '_pagination' => [
                                'description' => 'Set the start and the number of posts per page.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    // 'cursor' => [
                                    //     'label' => 'Start',
                                    //     'type' => 'yooessentials-number',
                                    //     'default' => 1,
                                    //     'source' => true,
                                    //     'attrs' => [
                                    //         'min' => 1,
                                    //         'placeholder' => 1,
                                    //     ],
                                    // ],
                                    'limit' => [
                                        'label' => 'Count',
                                        'type' => 'yooessentials-number',
                                        'default' => 50,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 100,
                                            'placeholder' => 10,
                                        ],
                                    ],
                                    'sort' => [
                                        'label' => 'Sort',
                                        'type' => 'select',
                                        'default' => 'top',
                                        'options' => [
                                            'Top' => 'top',
                                            'Latest' => 'latest',
                                        ],
                                    ],
                                ],
                            ],
                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        /** @var BlueskySource */
        $source = self::loadSource($args, BlueskySource::class);

        if (!$source) {
            return [];
        }

        $posts = self::resolveFromCache($source, $args, function () use ($source, $args) {
            $did = $source->api()->getProfile($source->actor())['did'] ?? '';

            $args['author'] = $did; // Limitted to actor defined in Source
            $args['q'] ??= $did; // If no Query persent, use actor did as query

            return $source->api()->searchPosts($args);
        });

        return $posts;
    }
}
