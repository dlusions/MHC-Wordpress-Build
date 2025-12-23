<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleBusinessProfileLocationType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileLocation';
    public const LABEL = 'Location';

    public const WEEKDAYS = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'title' => [
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
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveDescription',
                    ],
                ],
                'primaryPhone' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Phone',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolvePhone',
                    ],
                ],
                'websiteUri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Website',
                    ],
                ],
                'primaryCategory' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Category',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveCategory',
                    ],
                ],
                'labels_string' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Labels',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveLabels',
                    ],
                ],
                'languageCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Language',
                    ],
                ],
                'storeCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Store Code',
                    ],
                ],
                'coordinates' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Coordinates',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveCoordinates',
                    ],
                ],
                'latitude' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Coordinates (Latitude)',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveLatitude',
                    ],
                ],
                'longitude' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Coordinates (Longitude)',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveLongitude',
                    ],
                ],
                'totalReviewCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Reviews',
                    ],
                ],
                'averageRating' => [
                    'type' => 'Float',
                    'metadata' => [
                        'label' => 'Average Rating',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveAverageRating',
                    ],
                ],
                'reviewsUri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Reviews URI',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveReviewsUri',
                    ],
                ],
                'newReviewUri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'New Review URI',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveNewReviewUri',
                    ],
                ],
                'mapsUri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Google Maps URI',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMapsUri',
                    ],
                ],
                'placeId' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Google Maps Place ID',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolvePlaceId',
                    ],
                ],
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
                'storefrontAddress' => [
                    'type' => GoogleBusinessProfilePostalAddressType::NAME,
                    'metadata' => [
                        'label' => 'Address',
                    ],
                ],
                'regularHours' => [
                    'type' => ['listOf' => GoogleBusinessProfilePeriodType::NAME],
                    'args' => [
                        'startday' => [
                            'type' => 'Int',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Opening Hours',
                        'arguments' => [
                            'startday' => [
                                'label' => 'Start Day',
                                'description' => 'Set the day of the week to start displaying hours from.',
                                'type' => 'select',
                                'default' => 1,
                                'options' => array_flip(self::WEEKDAYS),
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveRegularHours',
                    ],
                ],
                'specialHours' => [
                    'type' => ['listOf' => GoogleBusinessProfilePeriodType::NAME],
                    'metadata' => [
                        'label' => 'Special Hours',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveSpecialHours',
                    ],
                ],
                'moreHours' => [
                    'type' => ['listOf' => GoogleBusinessProfilePeriodType::NAME],
                    'args' => [
                        'category' => [
                            'type' => 'String',
                        ],
                        'startday' => [
                            'type' => 'Int',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'More Hours',
                        'arguments' => [
                            'category' => [
                                'label' => 'Category',
                                'type' => 'select',
                                'description' => 'Set the category which you want to display hours for.',
                                'options' => [
                                    'Access' => 'ACCESS',
                                    'Breakfast' => 'BREAKFAST',
                                    'Brunch' => 'BRUNCH',
                                    'Delivery' => 'DELIVERY',
                                    'Dinner' => 'DINNER',
                                    'Drive-through' => 'DRIVE_THROUGH',
                                    'Happy hours' => 'HAPPY_HOUR',
                                    'Hours for the elderly' => 'SENIOR_HOURS',
                                    'Kitchen' => 'KITCHEN',
                                    'Lunch' => 'LUNCH',
                                    'Online operating hours' => 'ONLINE_SERVICE_HOURS',
                                    'Pick Up' => 'PICKUP',
                                    'Takeaway' => 'TAKEAWAY',
                                ],
                            ],
                            'startday' => [
                                'label' => 'Start Day',
                                'description' => 'Set the day of the week to start displaying hours from.',
                                'type' => 'select',
                                'default' => 1,
                                'options' => array_flip(self::WEEKDAYS),
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMoreHours',
                    ],
                ],
            ],
        ];
    }

    public static function resolveAverageRating(array $location): string
    {
        return round($location['averageRating'] ?? 0, 1);
    }

    public static function resolveCoordinates(array $location): string
    {
        return self::resolveLatitude($location) . ',' . self::resolveLongitude($location);
    }

    public static function resolveLatitude(array $location): string
    {
        return $location['latlng']['latitude'] ?? '';
    }

    public static function resolveDescription(array $location): string
    {
        return $location['profile']['description'] ?? '';
    }

    public static function resolveLongitude(array $location): string
    {
        return $location['latlng']['longitude'] ?? '';
    }

    public static function resolvePhone(array $location): string
    {
        return $location['phoneNumbers']['primaryPhone'] ?? '';
    }

    public static function resolvePhones(array $location): array
    {
        return $location['phoneNumbers']['additionalPhones'] ?? [];
    }

    public static function resolveCategory(array $location): string
    {
        return $location['categories']['primaryCategory']['displayName'] ?? '';
    }

    public static function resolveCategories(array $location): array
    {
        $categories = $location['categories']['additionalCategories'] ?? [];

        return array_filter(
            array_map(fn ($category) => $category['displayName'] ?? null, $categories)
        );
    }

    public static function resolveLabels(array $location): string
    {
        return implode(', ', $location['labels'] ?? []);
    }

    public static function resolvePlaceId(array $location): string
    {
        return $location['metadata']['placeId'] ?? '';
    }

    public static function resolveMapsUri(array $location): string
    {
        return $location['metadata']['mapsUri'] ?? '';
    }

    public static function resolveReviewsUri(array $location): string
    {
        $placeId = self::resolvePlaceId($location);

        return "https://search.google.com/local/reviews?placeid=$placeId";
    }

    public static function resolveNewReviewUri(array $location): string
    {
        return $location['metadata']['newReviewUri'] ?? '';
    }

    public static function resolveRegularHours($location, array $args): array
    {
        $periods = $location['regularHours']['periods'] ?? [];
        $periods = self::mergePeriods($periods);
        $periods = self::parseWeekdaysPeriods($periods, $args);

        return $periods;
    }

    public static function resolveSpecialHours($location, array $args): array
    {
        $periods = $location['specialHours']['specialHourPeriods'] ?? [];
        $periods = self::mergePeriods($periods);

        return $periods;
    }

    public static function resolveMoreHours($location, array $args): array
    {
        $category = $args['category'] ?? '';

        $moreHours = $location['moreHours'] ?? [];
        $moreHours = Arr::find($moreHours, fn ($v) => $v['hoursTypeId'] === $category);

        $periods = $moreHours['periods'] ?? [];
        $periods = self::mergePeriods($periods);
        $periods = self::parseWeekdaysPeriods($periods, $args);

        return $periods;
    }

    protected static function mergePeriods(array $periods): array
    {
        $result = [];

        foreach ($periods as $i => $period) {
            $day = $period['openDay'] ?? '';

            // support for special hours which have no openDay
            if (empty($day) && isset($period['startDate'])) {
                $day = implode('', array_values($period['startDate']));
            }

            $day = ucfirst(strtolower($day));

            $period['openDay'] = $day;
            unset($period['closeDay']); // not needed

            // support for past midnight hours in which case
            // the closeTime is set on the next period
            if (($period['closeTime']['hours'] ?? null) === 24) {
                $nextPeriod = isset($periods[$i + 1]) ? $periods[$i + 1] : $periods[0];

                if (empty($nextPeriod['openTime']) && !empty($nextPeriod['closeTime'])) {
                    $period['closeTime'] = $nextPeriod['closeTime'];
                }
            }

            // process same day periods
            if (isset($result[$day])) {
                // support for periods that exceed current day
                // if (empty($period['openTime']) && !empty($period['closeTime'])) {
                //     $period['openTime'] = $result[$day]['closeTime'];
                // }

                if (!empty($period['openTime']) && !empty($period['closeTime'])) {
                    $result[$day]['periods'][] = [
                        'openTime' => $period['openTime'],
                        'closeTime' => $period['closeTime'],
                    ];
                }

                $result[$day]['closeTime'] = $period['closeTime'];

                continue;
            }

            // default period
            if (!empty($period['openTime']) && !empty($period['closeTime'])) {
                $period['periods'][] = [
                    'openTime' => $period['openTime'],
                    'closeTime' => $period['closeTime'],
                ];
            }

            $result[$day] = $period;
        }

        return $result;
    }

    protected static function parseWeekdaysPeriods(array $periods, array $args): array
    {
        $days = self::WEEKDAYS;
        $startday = $args['startday'] ?? 1;

        // sort as per week start day
        $days = array_merge(array_slice($days, $startday), array_slice($days, 0, $startday));

        $result = [];

        foreach ($days as $day) {
            if (isset($periods[$day])) {
                $result[] = $periods[$day];

                continue;
            }

            $result[] = [
                'openDay' => $day,
                'closed' => true,
            ];
        }

        return $result;
    }
}
