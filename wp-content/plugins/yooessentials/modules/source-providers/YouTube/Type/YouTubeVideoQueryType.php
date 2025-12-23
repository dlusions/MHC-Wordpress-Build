<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube\Type;

use ZOOlanders\YOOessentials\Source\Provider\YouTube\YouTubeSource;

class YouTubeVideoQueryType extends YouTubeVideosQueryType
{
    public const NAME = 'video';
    public const LABEL = 'Video';
    public const DESCRIPTION = 'Single video';

    public static function getCacheKey(): string
    {
        return 'youtube-video';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => YouTubeVideoType::NAME,

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                        'channelId' => [
                            'type' => 'String',
                        ],
                        'location' => [
                            'type' => 'String',
                        ],
                        'locationRadius' => [
                            'type' => 'String',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'publishedAfter' => [
                            'type' => 'String',
                        ],
                        'publishedBefore' => [
                            'type' => 'String',
                        ],
                        'q' => [
                            'type' => 'String',
                        ],
                        'regionCode' => [
                            'type' => 'String',
                        ],
                        'relevanceLanguage' => [
                            'type' => 'String',
                        ],
                        'videoDuration' => [
                            'type' => 'String',
                        ],
                        'videoDefinition' => [
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
                            'id' => [
                                'label' => 'Video ID',
                                'description' => 'Set a video ID or use bellow filters to specify which video should be loaded.',
                                'source' => true,
                            ],
                            'channelId' => [
                                'label' => 'Channel',
                                'description' => 'Restricts the results to videos created by a specific channel ID.',
                                'source' => true,
                                'enable' => '!id',
                            ],
                            'q' => [
                                'label' => 'Query Term',
                                'description' => 'Use the Boolean NOT (-) and OR (|) operators to exclude or find videos that are associated with one of several search terms. For example, to match either "boating" or "sailing", set as <code>boating|sailing</code>. Similarly, to exclude "fishing", set as <code>boating|sailing -fishing</code>.',
                                'source' => true,
                                'enable' => '!id',
                            ],
                            '_after_before' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'publishedAfter' => [
                                        'label' => 'Since',
                                        'description' => 'At or after the specified date.',
                                        'source' => true,
                                        'enable' => '!id',
                                        'attrs' => [
                                            'type' => 'datetime-local',
                                        ],
                                    ],
                                    'publishedBefore' => [
                                        'label' => 'Until',
                                        'description' => 'Before or at the specified date.',
                                        'source' => true,
                                        'enable' => '!id',
                                        'attrs' => [
                                            'type' => 'datetime-local',
                                        ],
                                    ],
                                ],
                            ],
                            '_geo' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Location in conjunction with Radius, defines a circular geographic area to which to restrict the videos.',
                                'fields' => [
                                    'location' => [
                                        'label' => 'Location',
                                        'description' => 'The coordinates that points at the center of the area.',
                                        'source' => true,
                                        'enable' => '!id',
                                        'attrs' => [
                                            'placeholder' => 'lat,lon',
                                        ],
                                    ],
                                    'locationRadius' => [
                                        'label' => 'Radius',
                                        'description' => 'The maximum distance from the location in <b>m</b>, <b>km</b>, <b>ft</b>, or <b>mi</b> units.',
                                        'source' => true,
                                        'enable' => '!id',
                                    ],
                                ],
                            ],
                            '_region_language' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Restricts the videos to those that can be viewed in a specific country and/or are in a relevant language. Note that videos in other languages will still be returned if they are highly relevant.',
                                'fields' => [
                                    'regionCode' => [
                                        'label' => 'Region',
                                        'description' => 'A <code>ISO 3166-1 alpha-2</code> country code.',
                                        'source' => true,
                                        'enable' => '!id',
                                    ],
                                    'relevanceLanguage' => [
                                        'label' => 'Language',
                                        'description' => 'A <code>ISO 639-1 two-letter</code> language code.',
                                        'source' => true,
                                        'enable' => '!id',
                                    ],
                                ],
                            ],
                            '_definition_duration' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Restricts the results to videos that have the specified quality and/or duration.',
                                'fields' => [
                                    'videoDefinition' => [
                                        'label' => 'Definition',
                                        'type' => 'select',
                                        'default' => 'any',
                                        'enable' => '!id',
                                        'options' => [
                                            'Any' => 'any',
                                            'High (HD)' => 'high',
                                            'Standard (SD)' => 'standard',
                                        ],
                                    ],
                                    'videoDuration' => [
                                        'label' => 'Duration',
                                        'type' => 'select',
                                        'default' => 'any',
                                        'enable' => '!id',
                                        'options' => [
                                            'Any' => 'any',
                                            'Longer than 20m' => 'long',
                                            'Between 4 and 20m' => 'medium',
                                            'Shorter than 4m' => 'short',
                                        ],
                                    ],
                                ],
                            ],
                            'offset' => [
                                'label' => 'Start',
                                'description' => 'The starting point of videos to retrieve.',
                                'type' => 'yooessentials-number',
                                'default' => 0,
                                'modifier' => 1,
                                'source' => true,
                                'enable' => '!id',
                                'attrs' => [
                                    'min' => 1,
                                ],
                            ],
                            'order' => [
                                'label' => 'Order',
                                'description' => 'The order by which to sort the videos.',
                                'type' => 'select',
                                'default' => 'relevance',
                                'enable' => '!id',
                                'options' => [
                                    'Relevance' => 'relevance',
                                    'Rating Descendant' => 'rating',
                                    'Created Date Reversed' => 'date',
                                    'Title Alphabetical' => 'title',
                                    'View Count Descendant' => 'viewCount',
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
        /** @var YouTubeSource */
        $source = self::loadSource($args, YouTubeSource::class);

        if (!$source) {
            return [];
        }

        $args += ['offset' => 0];

        return self::resolveFromCache($source, $args, function () use ($source, $args) {
            $ids = !empty($args['id']) ? [trim($args['id'])] : self::queryVideosId($source, $args);

            return $source->api()->video(array_pop($ids)) ?? [];
        });
    }
}
