<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar\Type;

use YOOtheme\GraphQL\Type\Definition\ResolveInfo;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleCalendarEventType extends GenericType
{
    public const NAME = 'GoogleCalendarEvent';
    public const LABEL = 'Event';

    public function config(): array
    {
        return [
            'fields' => [
                'summary' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
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
                'start' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Start',
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveDate',
                    ],
                ],
                'end' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'End',
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveDate',
                    ],
                ],
                'eventType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                    ],
                ],
                'status' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Status',
                    ],
                ],
                'visibility' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Visibility',
                    ],
                ],
                'location' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Location',
                        'filters' => ['limit'],
                    ],
                ],
                'created' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created',
                        'filters' => ['date'],
                    ],
                ],
                'updated' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Updated',
                        'filters' => ['date'],
                    ],
                ],
                'htmlLink' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Event Link',
                    ],
                ],
                'hangoutLink' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Hangout Link',
                    ],
                ],
                'creator' => [
                    'type' => GoogleCalendarProfileType::NAME,
                    'metadata' => [
                        'label' => 'Creator',
                    ],
                ],
                'organizer' => [
                    'type' => GoogleCalendarProfileType::NAME,
                    'metadata' => [
                        'label' => 'Organizer',
                    ],
                ],
                'attendees' => [
                    'type' => [
                        'listOf' => GoogleCalendarAttendeeType::NAME
                    ],
                    'metadata' => [
                        'label' => 'Attendees',
                    ],
                ],
                'attachments' => [
                    'type' => [
                        'listOf' => GoogleCalendarAttachmentType::NAME
                    ],
                    'metadata' => [
                        'label' => 'Attachments',
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

    public static function resolveDate(
        array $event,
        $args = [],
        $context = '',
        ?ResolveInfo $info = null
    ): string {
        return $event[$info->fieldName]['dateTime'] ?? $event[$info->fieldName]['date'] ?? '';
    }
}
