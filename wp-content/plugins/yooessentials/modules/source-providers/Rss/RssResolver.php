<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss;

use ZOOlanders\YOOessentials\Source\Resolver\AbstractResolver;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\InMemoryFilters;
use ZOOlanders\YOOessentials\Source\Resolver\HasQueryMode;
use ZOOlanders\YOOessentials\Source\Resolver\Orders\InMemoryOrders;
use ZOOlanders\YOOessentials\Source\Resolver\QueryMode;
use ZOOlanders\YOOessentials\Source\Resolver\SourceResolver;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Util\Arr;

class RssResolver extends AbstractResolver
{
    use HasQueryMode, HasDynamicFields;

    public function fromArgs(array $args, $root): SourceResolver
    {
        return $this->offset($args['offset'] ?? self::DEFAULT_OFFSET)
            ->limit($args['limit'] ?? self::DEFAULT_LIMIT)
            ->mode($args['mode'] ?? QueryMode::MODE_AND)
            ->orders($args['ordering'] ?? [], $root)
            ->filters($args['filters'] ?? [], $root);
    }

    protected function makeFilters(array $filters): InMemoryFilters
    {
        return new InMemoryFilters($filters, $this->mode);
    }

    protected function makeOrders(array $orders): InMemoryOrders
    {
        return new InMemoryOrders($orders);
    }

    public function resolve(): array
    {
        /** @var RssFeedItem[] $items */
        $items = $this->source()
            ->rss()
            ->items();

        if ($this->hasFilters()) {
            $items = Arr::filter($items, fn (array $record) => $this->filters->apply($record));
        }

        if ($this->hasOrders()) {
            usort($items, fn (array $recordA, array $recordB) => $this->orders->apply($recordA, $recordB));
        }

        $records = [];
        foreach ($items as $row) {
            $data = [];
            foreach ($row as $key => $value) {
                $data[self::encodeField($key)] = $value;
            }
            $records[] = $data;
        }

        return array_slice($records, $this->offset, $this->limit);
    }
}
