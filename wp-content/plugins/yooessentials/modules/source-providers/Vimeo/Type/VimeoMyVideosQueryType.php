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

class VimeoMyVideosQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'myVideos';
    public const LABEL = 'My Videos';
    public const DESCRIPTION = 'List of videos';

    public static function getCacheKey(): string
    {
        return 'vimeo-my-videos';
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
                        'filter' => [
                            'type' => 'String',
                        ],
                        'filter_tag' => [
                            'type' => 'String',
                        ],
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
                        'fields' => [
                            'query' => [
                                'label' => 'Query',
                                'description' => 'Filter videos by a specific term.',
                                'source' => true,
                            ],
                            'filter' => [
                                'label' => 'Attribute',
                                'type' => 'select',
                                'description' => 'Filter by attribute.',
                                'default' => '',
                                'options' => [
                                    'None' => '',
                                    'Featured' => 'featured',
                                    'Live' => 'live',
                                    'No live' => 'nolive',
                                    // 'Return app-only videos.' => 'app_only',
                                    // 'Embeddable' => 'embeddable',
                                    // 'Playable videos' => 'playable',
                                    // 'Screen-recorded' => 'screen_recorded',
                                ],
                            ],
                            'filter_tag' => [
                                'label' => 'Tags',
                                'description' => 'Filter by tag, separate by comma multiple values.',
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
                                        'default' => 'default',
                                        'options' => [
                                            'Default' => 'default',
                                            'Alphabetical' => 'alphabetical',
                                            'Date' => 'date',
                                            'Modified' => 'modified_time',
                                            'Duration' => 'duration',
                                            'Last User Action' => 'last_user_action_event_date',
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

        if (!$source) {
            return [];
        }

        $videos = self::resolveFromCache($source, $args, function () use ($source, $args) {
            $args['fields'] = VimeoVideoType::fields();

            return (array) $source->api()->myVideos($args);
        });

        return $videos;
    }
}
