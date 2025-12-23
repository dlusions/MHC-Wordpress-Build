<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube\Type;

use DateTime;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\YouTube\YouTubeSource;
use ZOOlanders\YOOessentials\Util;

class YouTubeVideosQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'videos';
    public const LABEL = 'Videos';
    public const DESCRIPTION = 'List of videos';

    public const DEFAULT_MAX_RESULTS = 5;

    public static function getCacheKey(): string
    {
        return 'youtube-videos';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => YouTubeVideoType::NAME,
                    ],

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
                        'maxResults' => [
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
                                'label' => 'Videos ID',
                                'description' => 'Set videos ID separated by comma, or use bellow filters to specify which videos should be loaded.',
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
                            '_offset' => [
                                'description' => 'The starting point and the maximum amount of videos to retrieve.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'source' => true,
                                        'enable' => '!id',
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                    'maxResults' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => self::DEFAULT_MAX_RESULTS,
                                        'source' => true,
                                        'enable' => '!id',
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 50,
                                        ],
                                    ],
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
            $ids = !empty($args['id']) ? Util\Arr::explodeList($args['id']) : self::queryVideosId($source, $args);

            $items = $source->api()->videos($ids);

            return array_splice($items, $args['offset']);
        });
    }

    protected static function queryVideosId(YouTubeSource $source, $args): array
    {
        $filter = array_filter(
            Arr::pick($args, [
                'channelId',
                'location',
                'locationRadius',
                'order',
                'maxResults',
                'publishedAfter',
                'publishedBefore',
                'q',
                'regionCode',
                'relevanceLanguage',
                'videoDuration',
                'videoDefinition',
            ])
        );

        $filter['type'] = 'video';
        $filter['videoSyndicated'] = true;
        $filter['maxResults'] = $args['offset'] + ($args['maxResults'] ?? self::DEFAULT_MAX_RESULTS);
        $filter['q'] = str_replace('|', '%7C', $filter['q'] ?? '');

        $filter['publishedAfter'] = isset($filter['publishedAfter']) ? self::formatDate($filter['publishedAfter']) : null;
        $filter['publishedBefore'] = isset($filter['publishedBefore']) ? self::formatDate($filter['publishedBefore']) : null;

        $items = (array) $source->api()->search($filter);

        return array_map(fn ($item) => $item['id']['videoId'], $items);
    }

    private static function formatDate(string $date): ?string
    {
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $date);

        if (!$date) {
            return null;
        }

        return $date->format('Y-m-d\TH:i:s\Z');
    }
}
