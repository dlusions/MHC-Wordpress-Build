<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\GoogleCalendarSource;

class GoogleCalendarCalendarType extends GenericType
{
    use LoadsSourceFromArgs;

    public const NAME = 'GoogleCalendarCalendar';
    public const LABEL = 'Calendar';

    public function config(): array
    {
        return [
            'fields' => [
                'summary' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Summary',
                        'filters' => ['limit'],
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'location' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Location',
                        'filters' => ['limit'],
                    ],
                ],
                'timeZone' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Time Zone',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveEvents($calendar, array $args, $a): array
    {
        $source = self::loadSource($calendar, GoogleCalendarSource::class);

        if (!$source) {
            return [];
        }

        return (array) $source->api()->events($calendar['id']);
    }
}
