<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Csv\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\InMemoryFilter;
use ZOOlanders\YOOessentials\Source\Resolver\HasDynamicArgs;
use ZOOlanders\YOOessentials\Source\Resolver\HasFilterAndOrderFields;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Resolver\QueryMode;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Csv\CsvResolver;
use ZOOlanders\YOOessentials\Source\Provider\Csv\CsvSource;
use ZOOlanders\YOOessentials\Util\Arr;

class CsvRecordsQueryType extends AbstractQueryType
{
    use HasFilterAndOrderFields, CachesResolvedSourceData, LoadsSourceFromArgs, HasDynamicArgs, HasDynamicFields;

    private CsvFileType $csvType;

    public const NAME = 'records';
    public const LABEL = 'Records';
    public const DESCRIPTION = 'List of records';

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

    public function __construct(SourceInterface $source, CsvFileType $csvType)
    {
        parent::__construct($source);

        $this->csvType = $csvType;
    }

    public function config(): array
    {
        $args = [
            'source_id' => $this->source->id(),
        ];

        if (!$this->source->id()) {
            $args = array_merge($args, $this->source->config());
        }

        $headers = $this->getHeaders();

        return [
            'fields' => [
                $this->name() => [
                    'type' => ['listOf' => $this->csvType->name()],

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
                        'filters' => [
                            'type' => [
                                'listOf' => DynamicSourceInputType::nameForInputType(CsvFilterType::NAME),
                            ]
                        ],
                        'ordering' => [
                            'type' => [
                                'listOf' => DynamicSourceInputType::nameForInputType(CsvOrderingType::NAME),
                            ]
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
                                        InMemoryFilter::operators(),
                                        [
                                            'mode' => [
                                                'label' => 'Mode',
                                                'type' => 'select',
                                                'default' => 'AND',
                                                'enable' => 'filters.length > 1',
                                                'options' => [
                                                    'AND' => QueryMode::MODE_AND,
                                                    'OR' => QueryMode::MODE_OR,
                                                ],
                                            ],
                                        ],
                                        [
                                            'field' => [
                                                'label' => 'Field',
                                                'type' => 'yooessentials-select-dropdown',
                                                'options' => $headers,
                                            ],
                                        ]
                                    ),
                                ],
                            ],

                            '_ordering' => [
                                'label' => 'Ordering',
                                'type' => 'yooessentials-button-panel-ordering',
                                'description' => 'Set conditions to order the records depending on the content of a field.',
                                'panel' => [
                                    'title' => 'Ordering',
                                    'name' => 'yooessentials-query-conditions-ordering',
                                    'description' => 'Set conditions to order the records depending on the content of a field.',
                                    'fields' => $this->orderingFields([
                                        'field' => [
                                            'label' => 'Field',
                                            'type' => 'yooessentials-select-dropdown',
                                            'options' => $headers,
                                        ],
                                    ]),
                                ],
                            ],

                            '_offset' => [
                                'description' => 'Set the starting point and limit the number of rows.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'limit',
                                        'default' => 20,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
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

    public static function resolve($root, array $args): array
    {
        /** @var CsvSource */
        $source = self::loadSource($args, CsvSource::class);

        if (!$source) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => (new CsvResolver($source, $args, $root))->resolve());

        return [];
    }

    protected function getHeaders(): array
    {
        $headers = [];
        foreach ($this->source->csv()->getHeader() as $header) {
            $id = self::encodeField($header);
            $name = Str::titleCase(mb_convert_encoding($header ?? $id, 'ISO-8859-1', 'UTF-8')); // make sure to return no empty string utf-8 encoded

            $headers[$name] = $id;
        }

        return $headers;
    }

    public static function getCacheKey(): string
    {
        return 'csv-records';
    }

    protected static function resolveArgs(array $args, $root): array
    {
        $dynamicArgs = ['filters', 'ordering', 'orderings'];

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
