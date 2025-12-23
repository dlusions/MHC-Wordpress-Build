<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar;

return [
    'routes' => [
        [
            'post',
            GoogleCalendarController::PRESAVE_ENDPOINT,
            GoogleCalendarController::class . '@presave',
        ],
        [
            'post',
            GoogleCalendarController::GET_CALENDARS_ENDPOINT,
            GoogleCalendarController::class . '@calendars',
        ],
        [
            'post',
            GoogleCalendarController::GET_EVENTS_ENDPOINT,
            GoogleCalendarController::class . '@events',
        ],
    ],

    'yooessentials-sources' => [
        'google-calendar' => GoogleCalendarSource::class,
    ],
];
