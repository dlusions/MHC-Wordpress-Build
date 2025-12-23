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
class GoogleCalendarAttachmentType extends GenericType
{
    public const NAME = 'GoogleCalendarAttachment';
    public const LABEL = 'Attachment';

    public function config(): array
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'filters' => ['limit'],
                    ],
                ],
                'mimeType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Mime Type',
                    ],
                ],
                'fileUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'File URL',
                    ],
                ],
                'iconLink' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Icon URL',
                    ],
                ],
                'fileId' => [
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
