<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Orders;

use ZOOlanders\YOOessentials\Database\DatabaseQuery;
use ZOOlanders\YOOessentials\Source\Provider\Database\DatabaseSource;

class DatabaseOrder extends Order
{
    protected DatabaseSource $source;

    public function __construct(array $config, DatabaseSource $source)
    {
        $this->source = $source;

        parent::__construct($config);
    }

    public function tableAlias(): string
    {
        return $this->config('table', $this->source->table());
    }

    public function apply(DatabaseQuery $query): DatabaseQuery
    {
        $field = $this->source->db()->quoteNameStr([$this->tableAlias(), $this->field()]);

        return $query->orderBy($field, $this->direction());
    }
}
