<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

trait HasFilterAndOrderFields
{
    protected static $commonFields = [
        'name' => [
            'label' => 'Name',
            'source' => true,
            'description' => 'A name to identify this condition.',
        ],
        'status' => [
            'type' => 'checkbox',
            'label' => 'Status',
            'text' => 'Disable condition',
            'description' => 'Disable the condition and publish it later.',
            'source' => true,
            'attrs' => [
                'true-value' => 'disabled',
                'false-value' => '',
            ],
        ],
    ];

    protected function orderingFields(array $extraOrderingFields = []): array
    {
        return [
            'ordering' => [
                'type' => 'yooessentials-dataset',
                'txtEmpty' => 'Add Condition',
                'titleMap' => 'name',
                'titleFallback' => 'Ordering Condition',
                'panel' => [
                    'title' => 'Ordering',
                    'name' => 'ordering',
                    'fields' => array_merge(self::$commonFields, $extraOrderingFields, [
                        'direction' => [
                            'label' => 'Direction',
                            'type' => 'select',
                            'default' => 'ASC',
                            'source' => true,
                            'options' => [
                                'ASC' => 'ASC',
                                'DESC' => 'DESC',
                            ],
                            'description' => 'The direction to use as ordering condition.',
                        ],
                    ]),
                    'fieldset' => [
                        'default' => [
                            'type' => 'tabs',
                            'fields' => [
                                [
                                    'title' => 'Condition',
                                    'fields' => ['table', 'field', 'direction'],
                                ],
                                [
                                    'title' => 'Advanced',
                                    'fields' => ['name', 'status', 'source'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function filterFields(array $operators, array $extraFields = [], array $extraFilterFields = []): array
    {
        return array_merge(
            [
                'filters' => [
                    'type' => 'yooessentials-dataset',
                    'txtEmpty' => 'Add condition',
                    'titleMap' => 'name',
                    'titleFallback' => 'Filter Condition',
                    'panel' => [
                        'title' => 'Filter',
                        'name' => 'filter',
                        'fields' => array_merge(self::$commonFields, $extraFilterFields, [
                            'operator' => [
                                'label' => 'Operator',
                                'type' => 'select',
                                'default' => '=',
                                'options' => $operators,
                                'description' => 'The operator for the condition evaluation.',
                            ],
                            'value' => [
                                'label' => 'Value',
                                'source' => true,
                                'description' => 'The value that is expected to be matched during evaluation.',
                                'show' => 'operator !== "empty" && operator !== "!empty" && operator !== "null" && operator !== "!null"',
                            ],
                        ]),
                        'fieldset' => [
                            'default' => [
                                'type' => 'tabs',
                                'fields' => [
                                    [
                                        'title' => 'Condition',
                                        'fields' => ['relation', 'field', 'operator', 'value'],
                                    ],
                                    [
                                        'title' => 'Advanced',
                                        'fields' => ['name', 'status', 'source'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $extraFields
        );
    }
}
