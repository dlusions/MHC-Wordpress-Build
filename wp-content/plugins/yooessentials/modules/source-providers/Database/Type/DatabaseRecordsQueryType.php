<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\TypeInterface;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\ListsTables;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\DatabaseFilter;
use ZOOlanders\YOOessentials\Source\Resolver\HasDynamicArgs;
use ZOOlanders\YOOessentials\Source\Resolver\HasFilterAndOrderFields;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Database\DatabaseSource;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\DatabaseResolver;
use ZOOlanders\YOOessentials\Util;

class DatabaseRecordsQueryType extends AbstractQueryType
{
    use HasFilterAndOrderFields, CachesResolvedSourceData, LoadsSourceFromArgs, HasDynamicArgs, ListsTables;

    public const NAME = 'records';
    public const LABEL = 'Records';
    public const DESCRIPTION = 'List of records';

    protected TypeInterface $objectType;

    public function __construct(SourceInterface $source, TypeInterface $objectType)
    {
        parent::__construct($source);

        $this->objectType = $objectType;
    }

    public function config(): array
    {
        $args = [
            'source_id' => $this->source->id(),
        ];

        if (!$this->source->id()) {
            $args = array_merge($args, $this->source->config());
        }

        $tableOptions = $this->getTableOptions();

        return [
            'fields' => [
                self::NAME => [
                    'type' => ['listOf' => $this->objectType->name()],

                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],

                        'limit' => [
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

                            '_offset_limit' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the starting point and limit the number of records.',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'default' => DatabaseResolver::DEFAULT_OFFSET,
                                        'source' => true,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'placeholder' => DatabaseResolver::DEFAULT_OFFSET + 1,
                                            'min' => 1,
                                        ],
                                    ],

                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => DatabaseResolver::DEFAULT_LIMIT,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 0,
                                        ],
                                    ],
                                ],
                            ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => $args,
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        /** @var DatabaseSource */
        $source = self::loadSource($args, DatabaseSource::class);

        if (!$source) {
            return [];
        }

        return self::resolveFromCache($source, $args, $root, fn () => (new DatabaseResolver($source, $args, $root))->resolve());
    }

    public static function getCacheKey(): string
    {
        return 'database-records';
    }

    protected static function resolveArgs(array $args, $root): array
    {
        $dynamicArgs = ['filters', 'orderings', 'ordering'];

        foreach ($dynamicArgs as $arg) {
            $args[$arg] = Util\Arr::map($args[$arg] ?? [], function ($dynamic) use ($root) {
                if (!isset($dynamic['source'])) {
                    return $dynamic;
                }

                return self::resolveDynamicArguments($dynamic, $root);
            });
        }

        return $args;
    }
}
