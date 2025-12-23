<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Type;

use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\DatabaseFilter;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\DatabaseResolver;

class DatabaseRecordQueryType extends DatabaseRecordsQueryType
{
    public const NAME = 'record';
    public const LABEL = 'Record';
    public const DESCRIPTION = 'Single record';

    public function config(): array
    {
        $tableOptions = $this->getTableOptions();

        return [
            'fields' => [
                self::NAME => [
                    'type' => $this->objectType->name(),

                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],

                        'mode' => [
                            'type' => 'String',
                        ],

                        'query' => [
                            'type' => 'String',
                        ],

                        'filters' => [
                            'type' => [
                                'listOf' => DynamicSourceInputType::nameForInputType(DatabaseFilterType::NAME),
                            ],
                            'defaultValue' => [],
                        ],

                        'ordering' => [
                            'type' => [
                                'listOf' => DynamicSourceInputType::nameForInputType(DatabaseOrderingType::NAME),
                            ],
                            'defaultValue' => [],
                        ],

                        'random_order' => [
                            'type' => 'Boolean',
                        ],

                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            '_filters' => [
                                'label' => 'Filters',
                                'type' => 'yooessentials-button-panel-filters',
                                'description' => 'Set conditions to filter the records depending on the content of a field.',
                                'panel' => [
                                    'title' => 'Filters',
                                    'name' => 'yooessentials-query-conditions-filters',
                                    'description' => 'Set conditions to filter the records depending on the content of a field.',
                                    'fields' => $this->filterFields(
                                        DatabaseFilter::operators(),
                                        [
                                            'mode' => [
                                                'label' => 'Mode',
                                                'type' => 'select',
                                                'description' => 'Set as <code>AND</code> to require all conditions to pass, as <code>OR</code> for at least one, or write a custom evaluation logic.',
                                                'default' => 'AND',
                                                'enable' => 'filters.length > 1',
                                                'options' => [
                                                    'AND' => DatabaseResolver::MODE_AND,
                                                    'OR' => DatabaseResolver::MODE_OR,
                                                    'Custom' => DatabaseResolver::MODE_CUSTOM,
                                                ],
                                            ],
                                            'query' => [
                                                'type' => 'yooessentials-simple-query',
                                                'connection' => 'filters',
                                                'show' => 'filters.length > 1 && mode === "custom"',
                                                'description' => 'Set a custom evaluation query referencing each condition with brackets and it order number, e.g. <code>{1}</code>. Use <code>AND|OR</code> operators to form the evalution logic, e.g. <code>{1} AND {2}</code>. Add parenthesis <code>()</code> for altering the execution order, e.g. <code>({1} AND {2}) OR {3}</code>.',
                                            ],
                                        ],
                                        [
                                            'relation' => [
                                                'label' => 'Table',
                                                'type' => 'select',
                                                'source' => true,
                                                'default' => $this->source()->table(),
                                                'options' => $tableOptions,
                                                'show' => count($tableOptions) > 1,
                                            ],
                                            'field' => [
                                                'label' => 'Field',
                                                'type' => 'yooessentials-select-dropdown-async',
                                                'description' => 'The field name which value to evaluate.',
                                                'endpoint' => 'yooessentials/source/database/filter-fields',
                                                'meta' => false,
                                                'params' => [
                                                    'table_field_path' => 'relation',
                                                    'source_id' => $this->source->id(),
                                                ],
                                            ],
                                        ]
                                    ),
                                ],
                            ],

                            '_orderings' => [
                                'label' => 'Ordering',
                                'type' => 'yooessentials-button-panel-ordering',
                                'description' => 'Set conditions to order the records depending on the content of a field.',
                                'enable' => '!random_order',
                                'panel' => [
                                    'title' => 'Ordering',
                                    'name' => 'yooessentials-query-conditions-ordering',
                                    'description' => 'Set conditions to order the records depending on the content of a field.',
                                    'fields' => $this->orderingFields([
                                        'table' => [
                                            'label' => 'Table',
                                            'type' => 'select',
                                            'default' => $this->source()->table(),
                                            'options' => $tableOptions,
                                            'show' => count($tableOptions) > 1,
                                        ],
                                        'field' => [
                                            'label' => 'Field',
                                            'type' => 'yooessentials-select-dropdown-async',
                                            'description' => 'The field name which value to use as ordering.',
                                            'endpoint' => 'yooessentials/source/database/filter-fields',
                                            'meta' => false,
                                            'params' => [
                                                'table_field_path' => 'table',
                                                'source_id' => $this->source->id(),
                                            ],
                                        ],
                                    ]),
                                ],
                            ],

                            'random_order' => [
                                'type' => 'checkbox',
                                'text' => 'Random',
                            ],

                            'offset' => [
                                'label' => 'Start',
                                'type' => 'yooessentials-number',
                                'description' => 'Set the starting point to specify which record is loaded.',
                                'modifier' => 1,
                                'source' => true,
                                'default' => DatabaseResolver::DEFAULT_OFFSET,
                                'attrs' => [
                                    'placeholder' => DatabaseResolver::DEFAULT_OFFSET + 1,
                                    'min' => 1,
                                ],
                            ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        // force limit
        $args['limit'] = 1;

        $records = parent::resolve($root, $args);

        return array_shift($records) ?? [];
    }
}
