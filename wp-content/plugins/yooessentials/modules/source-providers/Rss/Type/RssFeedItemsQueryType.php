<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSource;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\InMemoryFilter;
use ZOOlanders\YOOessentials\Source\Resolver\HasDynamicArgs;
use ZOOlanders\YOOessentials\Source\Resolver\HasFilterAndOrderFields;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Resolver\QueryMode;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Rss\RssResolver;
use ZOOlanders\YOOessentials\Source\Provider\Rss\RssSource;
use ZOOlanders\YOOessentials\Util\Arr;

class RssFeedItemsQueryType extends AbstractQueryType
{
    use HasFilterAndOrderFields, CachesResolvedSourceData, LoadsSourceFromArgs, HasDynamicArgs, HasDynamicFields, HasSource;

    private RssFeedType $rssType;

    public const NAME = 'items';
    public const LABEL = 'Items';
    public const DESCRIPTION = 'List of feed items';

    public function __construct(SourceInterface $source, RssFeedType $rssType)
    {
        parent::__construct($source);

        $this->source = $source;
        $this->rssType = $rssType;
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
                    'type' => ['listOf' => $this->rssType->itemType()->name()],

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
                                'listOf' => DynamicSourceInputType::nameForInputType(RssFilterType::NAME),
                            ],
                        ],
                        'ordering' => [
                            'type' => [
                                'listOf' => DynamicSourceInputType::nameForInputType(RssOrderingType::NAME),
                            ],
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
                                'label' => 'Filter by Fields',
                                'type' => 'yooessentials-button-panel',
                                'text' => 'Filters',
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

                            '_orderings' => [
                                'label' => 'Order by Fields',
                                'type' => 'yooessentials-button-panel',
                                'text' => 'Ordering',
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
                                        'default' => 10,
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
        /** @var RssSource */
        $source = self::loadSource($args, RssSource::class);

        if (!$source) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => (new RssResolver($source, $args, $root))->resolve());
    }

    public static function getCacheKey(): string
    {
        return 'rss-feed-items';
    }

    protected function getHeaders(): array
    {
        $items = $this->source->rss()->items();

        if (count($items) <= 0) {
            return [];
        }

        $item = array_shift($items);
        $keys = array_keys($item);
        $headers = [];
        foreach ($keys as $header) {
            $id = self::encodeField($header);
            $name = RssFeedItemType::prepareLabel(mb_convert_encoding($header ?? $id, 'ISO-8859-1', 'UTF-8')); // make sure to return no empty string utf-8 encoded

            $headers[$name] = $id;
        }

        return $headers;
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
