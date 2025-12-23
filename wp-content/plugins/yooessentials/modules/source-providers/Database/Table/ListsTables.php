<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Table;

use YOOtheme\Str;

trait ListsTables
{
    protected function getListOfTables(?string $relationType = null): array
    {
        return array_reduce(
            array_filter((array) $this->source->relations(), function (Relation $relation) use ($relationType) {
                if ($relationType && $relation->type() !== $relationType) {
                    return false;
                }

                return $relation;
            }),
            function ($carry, Relation $relation) {
                $table = $relation->table();
                $name = Str::titleCase(Str::snakeCase($relation->name(), ' '));

                $carry["$name ($table)"] = $relation->tableAlias();

                return $carry;
            },
            []
        );
    }

    protected function getTableOptions(): array
    {
        return array_merge(
            [
                "{$this->source->config('name')} ({$this->source->table()})" => $this->source()->table(),
            ],
            $this->getListOfTables()
        );
    }
}
