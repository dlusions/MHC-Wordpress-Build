<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

// https://developers.google.com/calendar/api/v3/reference/events
class GoogleCalendarProfileType extends GenericType
{
    public const NAME = 'GoogleCalendarProfile';
    public const LABEL = 'Profile';

    public function config(): array
    {
        return [
            'fields' => [
                'email' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Email',
                    ],
                ],
                'displayName' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                        'filters' => ['date'],
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
}
