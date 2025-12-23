<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use function ZOOlanders\YOOessentials\trans;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleBusinessProfilePeriodType extends GenericType
{
    public const NAME = 'GoogleBusinessProfilePeriod';
    public const LABEL = 'Time Period';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'openDay' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Day',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveDay',
                            'args' => [
                                'field' => 'openDay',
                            ],
                        ],
                    ],
                ],
                'openDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Date',
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveDate',
                            'args' => [
                                'field' => 'startDate',
                            ],
                        ],
                    ],
                ],
                'openTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Opens At',
                        'filters' => ['time'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveTime',
                            'args' => [
                                'field' => 'openTime',
                            ],
                        ],
                    ],
                ],
                'closeTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Closes At',
                        'filters' => ['time'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveTime',
                            'args' => [
                                'field' => 'closeTime',
                            ],
                        ],
                    ],
                ],
                'openPeriod' => [
                    'type' => 'String',
                    'args' => [
                        'format' => [
                            'type' => 'String',
                        ],
                        'separator' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Open Hours',
                        'arguments' => [
                            'format' => [
                                'label' => 'Time Format',
                                'description' => 'Select or enter a time format.',
                                'type' => 'data-list',
                                'default' => '',
                                'options' => [
                                    '15:00 (G:i)' => 'G:i',
                                    '3:00 pm (g:i A)' => 'g:i a',
                                ],
                            ],
                            'separator' => [
                                'label' => 'Separator',
                                'description' => 'In case of multiple periods the separator will be used to implode the values.',
                                'default' => '<br>',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveOpenHours',
                        ],
                    ],
                ],
                'closed' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Closed'
                    ],
                ],
            ],
        ];
    }

    public static function resolveTime($period, array $args = []): ?string
    {
        $field = $args['field'] ?? '';

        if (empty($period[$field])) {
            return null;
        }

        return self::parseTime($period[$field]);
    }

    public static function resolveDay($period, array $args = []): ?string
    {
        $field = $args['field'] ?? '';

        if (isset($period[$field])) {
            return trans($period[$field]);
        }

        if (isset($period['startDate']) && is_array($period['startDate'])) {
            $startDay = $period['startDate'];

            $date = new \DateTime();
            $date->setDate($startDay['year'], $startDay['month'], $startDay['day']);

            return trans($date->format('l'));
        }

        return trans('Day of week unspecified');
    }

    public static function resolveDate($period, array $args = []): ?string
    {
        $field = $args['field'] ?? '';
        $value = $period[$field] ?? null;

        if (!$value) {
            return null;
        }

        $date = new \DateTime();
        $date->setDate($value['year'], $value['month'], $value['day']);

        return $date->format('Y-m-d');
    }

    public static function resolveOpenHours($period, array $args = []): ?string
    {
        if (empty($period['openTime']) && empty($period['closeTime'])) {
            return trans('Closed');
        }

        if (empty($period['openTime']['hours']) && ($period['closeTime']['hours'] ?? '') === 24) {
            return trans('Open 24 hours');
        }

        $format = $args['format'] ?? '';
        $format = !empty($format) ? $format : 'G:i';

        $separator = $args['separator'] ?? '';
        $separator = !empty($separator) ? $separator : '<br>';

        if (isset($period['periods'])) {
            $list = array_map(
                fn ($period) => self::formatPeriodHours($period['openTime'], $period['closeTime'], $format),
                $period['periods']
            );

            return implode($separator, $list);
        }

        return self::formatPeriodHours($period['openTime'], $period['closeTime'], $format);
    }

    protected static function formatPeriodHours(array $open, array $close, string $format): string
    {
        return date($format, strtotime(self::parseTime($open))) . ' - ' . date($format, strtotime(self::parseTime($close)));
    }

    protected static function parseTime(array $time): string
    {
        return ($time['hours'] ?? '00') . ':' . ($time['minutes'] ?? '00');
    }
}
