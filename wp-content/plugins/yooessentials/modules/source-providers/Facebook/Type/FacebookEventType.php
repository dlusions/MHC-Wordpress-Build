<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class FacebookEventType extends GenericType
{
    public const NAME = 'FacebookEvent';
    public const LABEL = 'Event';

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                ],
                'type' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'category' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Category',
                    ],
                ],
                'timezone' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'TimeZone',
                    ],
                ],
                'cover' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Cover',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::cover',
                    ],
                ],
                'owner' => [
                    'type' => FacebookUserType::NAME,
                    'metadata' => [
                        'label' => 'Owner',
                    ],
                ],
                'place' => [
                    'type' => FacebookPlaceType::NAME,
                    'metadata' => [
                        'label' => 'Place'
                    ],
                ],
                'start_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Start',
                        'filters' => ['date'],
                    ],
                ],
                'end_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'End',
                        'filters' => ['date'],
                    ],
                ],
                'created_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'scheduled_publish_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Published At',
                        'filters' => ['date'],
                    ],
                ],
                'updated_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Updated At',
                        'filters' => ['date'],
                    ],
                ],
                'is_draft' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Draft',
                    ],
                ],
                'is_online' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Online',
                    ],
                ],
                'is_canceled' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Cancelled',
                    ],
                ],
                'online_event_third_party_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Online Third-Party URL',
                    ],
                ],
                'online_event_format' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Online Format',
                    ],
                ],
                'ticket_uri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Ticket URI',
                    ],
                ],
                'ticketing_privacy_uri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Ticket Privacy URI',
                    ],
                ],
                'ticketing_terms_uri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Ticket Terms URI',
                    ],
                ],
                'interested_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Interested',
                    ],
                ],
                'attending_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Attending',
                    ],
                ],
                'maybe_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Attending (Maybe)',
                    ],
                ],
                'declined_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Declined',
                    ],
                ],
                'noreply_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total No Replied',
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

    public static function cover($event): ?string
    {
        $id = $event['cover']['id'] ?? null;
        $url = $event['cover']['source'] ?? null;

        if (isset($id, $url)) {
            return Util\File::cacheMedia($url, "facebook-event-cover-$id");
        }

        return null;
    }
}
