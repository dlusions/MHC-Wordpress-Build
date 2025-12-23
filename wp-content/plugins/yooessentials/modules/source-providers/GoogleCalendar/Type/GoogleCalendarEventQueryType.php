<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\GoogleCalendarSource;

class GoogleCalendarEventQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'event';
    public const LABEL = 'Event';
    public const DESCRIPTION = 'A single calendar event';

    public static function getCacheKey(): string
    {
        return 'google-calendar-event';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => GoogleCalendarEventType::NAME,

                    'args' => [
                        'eventId' => [
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
                            'eventId' => [
                                'label' => 'Event',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'Choose the Event which to query.',
                                'endpoint' => 'yooessentials/source/google/calendar/events',
                                'meta' => false,
                                'source' => true,
                                'params' => [
                                    'source_id' => $this->source->id(),
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
        $eventId = $args['eventId'] ?? '';

        if (!$source || empty($eventId)) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => (array) $source->api()->event($source->config('calendar'), $eventId));
    }
}
