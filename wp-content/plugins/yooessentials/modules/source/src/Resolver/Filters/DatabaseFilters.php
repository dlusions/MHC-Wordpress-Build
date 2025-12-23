<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Filters;

use ZOOlanders\YOOessentials\Source\Provider\Database\DatabaseSource;

class DatabaseFilters extends Filters
{
    private DatabaseSource $source;

    public function __construct(array $filters, DatabaseSource $source)
    {
        $this->source = $source;

        parent::__construct($filters);
    }

    protected function createFilter(array $filter): DatabaseFilter
    {
        return new DatabaseFilter($filter, $this->source);
    }
}
