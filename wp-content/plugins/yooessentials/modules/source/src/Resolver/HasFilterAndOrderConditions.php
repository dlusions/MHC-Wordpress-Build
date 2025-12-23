<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

use ZOOlanders\YOOessentials\Source\Resolver\Filters\Filters;
use ZOOlanders\YOOessentials\Source\Resolver\Orders\Orders;
use ZOOlanders\YOOessentials\Util\Arr;

trait HasFilterAndOrderConditions
{
    use HasDynamicArgs;

    protected ?Filters $filters = null;

    protected ?Orders $orders = null;

    abstract protected function makeFilters(array $filters): Filters;

    abstract protected function makeOrders(array $orders): Orders;

    public function filters(array $filters, $root): self
    {
        $this->filters = $this->makeFilters($this->resolveFiltersOrOrders(Arr::filter($filters), $root));

        return $this;
    }

    public function orders(array $orders, $root): self
    {
        $this->orders = $this->makeOrders($this->resolveFiltersOrOrders(Arr::filter($orders), $root));

        return $this;
    }

    public function resolveFiltersOrOrders(array $args, $root): array
    {
        return Arr::map($args, fn ($arg) => self::resolveDynamicArguments($arg, $root));
    }

    public function hasFilters(): bool
    {
        return $this->filters !== null && count($this->filters->enabled()) > 0;
    }

    public function hasOrders(): bool
    {
        return $this->orders !== null && count($this->orders->enabled()) > 0;
    }
}
