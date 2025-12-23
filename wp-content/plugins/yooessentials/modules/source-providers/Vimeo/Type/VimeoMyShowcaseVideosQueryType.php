<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Vimeo\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Vimeo\VimeoSource;

class VimeoMyShowcaseVideosQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'myShowcaseVideos';
    public const LABEL = 'My Showcase Videos';
    public const DESCRIPTION = 'List of videos from a specific showcase';

    public static function getCacheKey(): string
    {
        return 'vimeo-my-showcase-videos';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => VimeoVideoType::NAME,
                    ],

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                        'password' => [
                            'type' => 'String',
                        ],
                        // 'filter' => [
                        //     'type' => 'String',
                        // ],
                        // 'filter_tag' => [
                        //     'type' => 'String',
                        // ],
                        'query' => [
                            'type' => 'String',
                        ],
                        'sort' => [
                            'type' => 'String',
                        ],
                        'direction' => [
                            'type' => 'String',
                        ],
                        'page' => [
                            'type' => 'Int',
                        ],
                        'per_page' => [
                            'type' => 'Int',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            'id' => [
                                'label' => 'Showcase ID',
                                'description' => 'Set the ID of the showcase from which to fetch the videos.',
                                'source' => true,
                            ],
                            'password' => [
                                'label' => 'Password',
                                'description' => 'Set the password of the showcase, if proceeds.',
                                'source' => true,
                            ],
                            // 'filter' => [
                            //     'label' => 'Filter',
                            //     'type' => 'select',
                            //     'description' => 'The attribute by which to filter the results.',
                            //     'default' => '',
                            //     'options' => [
                            //         'None' => '',
                            //         'Embeddable' => 'embeddable',
                            //     ]
                            // ],

                            // 'filter_tag' => [
                            //     'label' => 'Filter Tag',
                            //     'description' => 'A comma-separated list of tags to filter on.'
                            // ],
                            'query' => [
                                'label' => 'Query',
                                'description' => 'Filter videos by a specific term.',
                                'source' => true,
                            ],
                            '_sort' => [
                                'description' => 'Set the sort and direction of videos.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'sort' => [
                                        'label' => 'Sort',
                                        'type' => 'select',
                                        'default' => 'manual',
                                        'options' => [
                                            'Default' => 'manual',
                                            'Alphabetical' => 'alphabetical',
                                            'Comments' => 'comments',
                                            'Date' => 'date',
                                            'Modified' => 'modified_time',
                                            'Duration' => 'duration',
                                            'Total Plays' => 'plays',
                                            'Total Likes' => 'likes',
                                        ],
                                    ],
                                    'direction' => [
                                        'label' => 'Direction',
                                        'type' => 'select',
                                        'default' => 'desc',
                                        'options' => [
                                            'Ascending' => 'asc',
                                            'Descending' => 'desc',
                                        ],
                                    ],
                                ],
                            ],
                            '_pagination' => [
                                'description' => 'Set the page and the number of videos per page.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'page' => [
                                        'label' => 'Page',
                                        'type' => 'yooessentials-number',
                                        'default' => 1,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'placeholder' => 1,
                                        ],
                                    ],
                                    'per_page' => [
                                        'label' => 'Per Page',
                                        'type' => 'yooessentials-number',
                                        'default' => 25,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 100,
                                            'placeholder' => 25,
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
        /** @var VimeoSource */
        $source = self::loadSource($args, VimeoSource::class);

        $showcaseId = $args['id'] ?? null;

        if (!$source || !$showcaseId) {
            return [];
        }

        $videos = self::resolveFromCache($source, $args, function () use ($source, $showcaseId, $args) {
            $args['fields'] = VimeoVideoType::fields();

            return (array) $source->api()->myShowcaseVideos($showcaseId, $args);
        });

        return $videos;
    }
}
