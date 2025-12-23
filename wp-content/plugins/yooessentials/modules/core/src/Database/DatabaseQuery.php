<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Database;

interface DatabaseQuery
{
    public function createForDatabase(Database $database): self;

    public function select($fields = '*'): self;

    public function from($table): self;

    public function leftJoin($table, $firstColumn, string $operator = '=', ?string $secondColumn = null): self;

    public function where($column, string $operator = '=', $value = null): self;

    public function whereRaw($query, $glue = 'AND'): self;

    public function whereIn($column, $values): self;

    public function limit($limit): self;

    public function offset($offset): self;

    public function orderBy(string $field, string $direction = 'ASC'): self;

    public function get(): array;
}
