<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\Type;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\GoogleCalendarSource;

class GoogleCalendarEventsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'events';
    public const LABEL = 'Events';
    public const DESCRIPTION = 'List of calendar events';

    public static function getCacheKey(): string
    {
        return 'google-calendar-events';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => GoogleCalendarEventType::NAME,
                    ],

                    'args' => [
                        'q' => [
                            'type' => 'String',
                        ],
                        'orderBy' => [
                            'type' => 'String',
                        ],
                        'maxResults' => [
                            'type' => 'Int',
                        ],
                        'timeMin' => [
                            'type' => 'String',
                        ],
                        'timeMax' => [
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
                            'q' => [
                                'label' => 'Query',
                                'description' => 'Filter events that match these terms in the fields summary, description, location, attendee\'s displayName and attendee\'s email.',
                                'source' => true,
                            ],
                            '_time_range' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the Lower and Upper bounds (exclusive) for an event\'s start and/or end time to filter by. Can be date without time, e.g. <code>2011-06-03</code> formated as <code>Y-m-d</code>, in which case the current time will be appended, or a timestamp with time zone offset, e.g. <code>2011-06-03T10:00:00-07:00</code> formated as <code>c</code>.',
                                'fields' => [
                                    'timeMin' => [
                                        'label' => 'Time Min',
                                        'type' => 'yooessentials-datetime',
                                        'small' => true,
                                        'source' => true,
                                    ],
                                    'timeMax' => [
                                        'label' => 'Time Max',
                                        'type' => 'yooessentials-datetime',
                                        'small' => true,
                                        'source' => true,
                                    ],
                                ],
                            ],
                            '_order_limit' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the order and limit the number of events.',
                                'fields' => [
                                    'orderBy' => [
                                        'label' => 'Order By',
                                        'type' => 'select',
                                        'options' => [
                                            'Default' => '',
                                            'Start Time (asc)' => 'startTime',
                                            'Updated (asc)' => 'updated',
                                        ],
                                    ],
                                    'maxResults' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 2500,
                                            'placeholder' => 250,
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
        $source = self::loadSource($args, GoogleCalendarSource::class);

        if (!$source) {
            return [];
        }

        $args['singleEvents'] = true;
        $args['timeZone'] = app()->config->get('yooessentials.timezone');

        return self::resolveFromCache($source, $args, fn () => (array) $source->api()->events($source->config('calendar'), $args));
    }
}
