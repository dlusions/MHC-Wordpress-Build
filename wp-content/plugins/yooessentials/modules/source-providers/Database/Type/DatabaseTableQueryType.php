<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Provider\Database\DatabaseSource;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\DatabaseResolver;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\ListsTables;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\DatabaseFilter;
use ZOOlanders\YOOessentials\Source\Resolver\HasDynamicArgs;
use ZOOlanders\YOOessentials\Source\Resolver\HasFilterAndOrderFields;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Util\Arr;

class DatabaseTableQueryType extends AbstractQueryType
{
    use CachesResolvedSourceData, ListsTables, HasFilterAndOrderFields, LoadsSourceFromArgs, HasDynamicArgs;

    public const NAME = 'table';
    public const LABEL = 'Table';
    public const DESCRIPTION = 'Table Information';

    public static function getCacheKey(): string
    {
        return 'database-table';
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
                    'type' => DatabaseTableMetaType::NAME,

                    'args' => [
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

        return self::resolveFromCache($source, $args, $root, fn () => (new DatabaseResolver($source, $args, $root))->count()->resolve());
    }

    protected static function resolveArgs(array $args, $root): array
    {
        $dynamicArgs = ['filters'];

        foreach ($dynamicArgs as $arg) {
            $args[$arg] = Arr::map($args[$arg] ?? [], function ($dynamic) use ($root) {
                if (!isset($dynamic['source'])) {
                    return $dynamic;
                }

                return self::resolveDynamicArguments($dynamic, $root);
            });
        }

        return $args;
    }
}
