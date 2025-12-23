<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\CloudflareStream\CloudflareStreamSource;

class CloudlfareStreamVideosQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'videos';
    public const LABEL = 'Videos';
    public const DESCRIPTION = 'List of videos';

    public static function getCacheKey(): string
    {
        return 'cloudflare-stream-videos';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => CloudflareStreamVideoType::NAME,
                    ],

                    'args' => [
                        'after' => [
                            'type' => 'String',
                        ],
                        'before' => [
                            'type' => 'String',
                        ],
                        'search' => [
                            'type' => 'String',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'status' => [
                            'type' => 'String',
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
                            'search' => [
                                'label' => 'Search',
                                'description' => 'Filter videos based on it name.',
                                'source' => true,
                            ],

                            '_datetime_range' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Filter videos created after or before a specific time.',
                                'fields' => [
                                    'after' => [
                                        'label' => 'Since',
                                        'source' => true,
                                        'attrs' => [
                                            'type' => 'datetime-local',
                                        ],
                                    ],
                                    'before' => [
                                        'label' => 'Until',
                                        'source' => true,
                                        'attrs' => [
                                            'type' => 'datetime-local',
                                        ],
                                    ],
                                ],
                            ],

                            '_basic_filter' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'status' => [
                                        'label' => 'Status',
                                        'type' => 'select',
                                        'description' => 'Filter by status.',
                                        'default' => 'ready',
                                        'options' => [
                                            'Ready' => 'ready',
                                            'Queued' => 'queued',
                                            'In Progress' => 'inprogress',
                                            'Downloading' => 'downloading',
                                            'Error' => 'error',
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'description' => 'The amount of videos to fetch.',
                                        'source' => true,
                                        'type' => 'yooessentials-number',
                                        'default' => 20,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 0,
                                            'max' => 1000,
                                        ],
                                    ],
                                ],
                            ],

                            // 'order_direction' => [
                            //     'label' => trans('Direction'),
                            //     'type' => 'select',
                            //     'default' => 'DESC',
                            //     'options' => [
                            //         trans('Ascending') => 'ASC',
                            //         trans('Descending') => 'DESC',
                            //     ],
                            //     'enable' => '!id',
                            // ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source()->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        /** @var CloudflareStreamSource */
        $source = self::loadSource($args, CloudflareStreamSource::class);

        if (!$source) {
            return [];
        }

        $streams = self::resolveFromCache($source, $args, fn () => (array) $source->api()->streams($args));

        foreach ($streams as &$stream) {
            $source->signStream($stream);
        }

        return $streams;
    }
}
