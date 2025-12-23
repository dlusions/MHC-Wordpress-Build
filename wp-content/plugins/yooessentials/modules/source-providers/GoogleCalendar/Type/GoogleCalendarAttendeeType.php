<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\Type;

use ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\Type\GoogleCalendarProfileType as TypeGoogleCalendarProfileType;

// https://developers.google.com/calendar/api/v3/reference/events
class GoogleCalendarAttendeeType extends TypeGoogleCalendarProfileType
{
    public const NAME = 'GoogleCalendarAttendee';
    public const LABEL = 'Attendee';

    public function config(): array
    {
        $parent = parent::config();

        return [
            'fields' => array_merge($parent['fields'], [
                'comment' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Comment',
                        'filters' => ['limit'],
                    ],
                ],
                'responseStatus' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Response Status',
                    ],
                ],
                'organizer' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Organizer',
                    ],
                ],
                'resource' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Resource',
                    ],
                ],
                'optional' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Optional',
                    ],
                ],
                'additionalGuests' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Additional Guests Count',
                    ],
                ]
            ]),

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }
}
