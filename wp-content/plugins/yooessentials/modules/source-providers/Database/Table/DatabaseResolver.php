<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Table;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Database\DatabaseQuery;
use ZOOlanders\YOOessentials\Source\Resolver\AbstractResolver;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\DatabaseFilter;
use ZOOlanders\YOOessentials\Source\Resolver\Filters\DatabaseFilters;
use ZOOlanders\YOOessentials\Source\Resolver\HasQueryMode;
use ZOOlanders\YOOessentials\Source\Resolver\Orders\DatabaseOrders;
use ZOOlanders\YOOessentials\Source\Resolver\QueryMode;
use ZOOlanders\YOOessentials\Source\Resolver\SourceResolver;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Component\Cache\Adapter\FilesystemAdapter;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\ItemInterface;

class DatabaseResolver extends AbstractResolver implements QueryMode
{
    use HasQueryMode, HasDynamicFields;

    protected SourceInterface $source;

    private $eagerLoads = [];

    private $queryStructure = null;

    private bool $randomOrdering = false;

    private ?bool $count = null;

    private $id;

    public function __construct(SourceInterface $source, array $args = [], array $root = [])
    {
        parent::__construct($source, $args, $root);

        $this->id = $this->source->id();
    }

    public function fromArgs(array $args, $root): SourceResolver
    {
        if ($args['random_order'] ?? false) {
            $this->randomOrdering = true;
        }

        $this->offset($args['offset'] ?? self::DEFAULT_OFFSET)
            ->limit($args['limit'] ?? self::DEFAULT_LIMIT)
            ->queryStructure($args['query'] ?? null)
            ->mode($args['mode'] ?? self::MODE_AND)
            ->filters($args['filters'] ?? [], $root)
            ->orders($args['ordering'] ?? [], $root);

        return $this;
    }

    protected function makeFilters(array $filters): DatabaseFilters
    {
        return new DatabaseFilters($filters, $this->source);
    }

    protected function makeOrders(array $orders): DatabaseOrders
    {
        return new DatabaseOrders($orders, $this->source);
    }

    public function queryStructure(?string $query = null): self
    {
        $this->queryStructure = $query;

        return $this;
    }

    public function id(?string $id = null): self
    {
        $this->id = $id;

        return $this;
    }

    public function count(bool $count = true): self
    {
        $this->count = $count;

        return $this;
    }

    public function resolve(): array
    {
        $query = $this->buildQuery();

        if (app()->config->get('app.isCustomizer')) {
            Event::emit('yooessentials.info', [
                'addon' => 'source',
                'provider' => 'database',
                'query' => (string) $query,
            ]);
        }

        try {
            return $this->queryData($query);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'database',
                'action' => 'db-resolve',
                'source' => "{$this->source->id()} - {$this->source->name()}",
                'error' => $e->getMessage(),
                'query' => (string) $query,
            ]);

            return [];
        }
    }

    public function buildQuery(): DatabaseQuery
    {
        /** @var DatabaseQuery $query */
        $query = app(DatabaseQuery::class)->createForDatabase($this->source->db());

        $tables = $this->tablesList();

        $fields = $this->selectFields($tables);

        $query = $query->select($fields)->from($this->source->table());

        if (!$this->count) {
            $query = $query->offset($this->offset)->limit($this->limit);
        }

        if ($this->source->hasRelations()) {
            $query = $this->addRelatedTables($query);
        }

        if (!$this->count && ($this->hasOrders() || $this->randomOrdering)) {
            $query = $this->addOrdering($query);
        }

        if (!$this->hasFilters()) {
            return $query;
        }

        return $query->whereRaw($this->buildFiltersWhere($query));
    }

    public function filtersOnMainTable(): array
    {
        return array_filter($this->filters->enabled(), fn (DatabaseFilter $filter) => $filter->tableAlias() === $this->source->table());
    }

    public function filtersByRelationType(string $type): array
    {
        return array_filter($this->filters->enabled(), function (DatabaseFilter $filter) use ($type) {
            $relation = $this->source->relationFromTableAlias($filter->tableAlias());
            if (!$relation) {
                return false;
            }

            return $relation->type() === $type;
        });
    }

    private function eagerLoadRelation(Relation $relation, DatabaseQuery $query, array $data): array
    {
        $eagerLoadQuery = $query->createForDatabase($this->source->db());

        // Take all the primary keys from the queried data (the main table)
        $ids = $this->pluckMainTablePrimaryKeys($relation, $data);

        if (count($ids) <= 0) {
            return [];
        }

        $tableFields = $this->aliasedFieldsForTable($relation->table(), $relation->tableAlias());

        try {
            $eagerLoadQuery = $eagerLoadQuery
                ->select($tableFields)
                ->from($this->quoteNameStr([$relation->table()]) . ' AS ' . $this->quoteNameStr([$relation->tableAlias()]))
                ->whereIn($this->quoteNameStr([$relation->tableAlias(), $relation->relatedTableKey()]), $ids);

            if (app()->config->get('app.isCustomizer')) {
                Event::emit('yooessentials.info', [
                    'addon' => 'source',
                    'provider' => 'database',
                    'query' => (string) $eagerLoadQuery,
                ]);
            }

            $data = $eagerLoadQuery->get();

            return array_map(fn ($relatedRow) => $this->removeTablePrefixFromData((array) $relatedRow, $relation->relationFieldName()), $data);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'database',
                'action' => 'load-relation',
                'relation' => $relation->name(),
                'source' => "{$this->source->id()} - {$this->source->name()}",
                'error' => $e->getMessage(),
                'query' => (string) $query,
            ]);

            return [];
        }
    }

    public function quoteNameStr(array $strArr): string
    {
        return $this->source()->db()->quoteNameStr($strArr);
    }

    private function quote($text)
    {
        return $this->source->db()->quote($text);
    }

    private function addRelatedTables(DatabaseQuery $query): DatabaseQuery
    {
        foreach ($this->source->hasManyRelations() as $relation) {
            $this->eagerLoads[] = $relation;
        }

        /** @var Relation $relation */
        foreach ($this->source->belongsToRelations() as $relation) {
            $relatedField = $this->quoteNameStr([$this->source->table(), $relation->mainTableKey()]);

            $relatedTableField = $this->quoteNameStr([$relation->tableAlias(), $relation->relatedTableKey()]);

            $query->leftJoin(
                $this->quoteNameStr([$relation->table()]) . ' AS ' . $this->quoteNameStr([$relation->tableAlias()]),
                $relatedField,
                '=',
                $relatedTableField
            );
        }

        return $query;
    }

    private function replaceTablePrefix(array $data): array
    {
        if (!$this->source->hasRelations()) {
            return $this->removeTablePrefixFromData($data, $this->source->table());
        }

        // each relation "field" is actually the array representation of the related object
        $return = $this->replaceBelongsToFieldWithObject($data);

        return $this->removeTablePrefixFromData($data, $this->source->table(), $return);
    }

    private function queryData(DatabaseQuery $query): array
    {
        if ($this->count) {
            $list = $query->get();

            return array_shift($list);
        }

        $data = $query->get();

        $eagerLoadedData = [];
        $eagerLoadedFields = [];
        /** @var Relation $eagerLoad */
        foreach ($this->eagerLoads as $eagerLoad) {
            $eagerLoadedData[$eagerLoad->relationFieldName()] = $this->eagerLoadRelation($eagerLoad, $query, $data);
            $eagerLoadedFields[$eagerLoad->relationFieldName()] = $eagerLoad->relatedTableKey();
        }

        $data = array_map(fn ($row) => $this->replaceTablePrefix((array) $row), $data);

        $data = array_map(function ($row) use ($eagerLoadedData, $eagerLoadedFields) {
            foreach ($eagerLoadedData as $relatedField => $relatedData) {
                $row[$relatedField] = array_filter($relatedData, fn (array $item) => $item[$eagerLoadedFields[$relatedField]] === $row[$this->source->primaryKey()]);
                ;
            }

            $values = [];
            foreach ($row as $key => $value) {
                $values[self::encodeField($key)] = $value;
            }

            return $values;
        }, $data);

        return $data;
    }

    private function tablesList(): array
    {
        $tables = [$this->source->table() => $this->source->table()];

        // No relation set, just SELECT FROM the main table
        if (!$this->source->hasBelongsToRelations()) {
            return $tables;
        }

        // Belongs To needs a LEFT JOIN for each of the tables, with a dedicated alias
        foreach ($this->source->belongsToRelations() as $relation) {
            $tables[$relation->tableAlias()] = $relation->table();
        }

        return $tables;
    }

    private function aliasedFieldsForTable(string $tbl, string $tblAlias): array
    {
        /** @var FilesystemAdapter $cache */
        $cache = app(CacheInterface::class);
        $cacheKey = 'database-table-fields-' . sha1(json_encode($this->source->config() + ['fields_for_table' => $tbl]));

        $tableFields = $cache->get($cacheKey, function (ItemInterface $item) use ($tbl, $tblAlias) {
            // Get the list of fields for the table
            $tableFields = array_keys($this->source->tableColumns($tbl));

            // build a list of `field` AS `alias` to avoid naming conflicts
            return array_map(function ($field) use ($tbl, $tblAlias) {
                $alias = $this->quoteNameStr([$tblAlias . '_' . $field]);
                $field = $this->quoteNameStr([$tblAlias, $field]);

                return $field . ' AS ' . $alias;
            }, $tableFields);
        });

        // avoid caching empty list
        if (!$tableFields || empty($tableFields)) {
            $cache->delete($cacheKey);
        }

        return $tableFields;
    }

    private function buildQueryStructure(): string
    {
        if ($this->mode === self::MODE_CUSTOM && $this->queryStructure) {
            return $this->queryStructure;
        }

        if ($this->mode === self::MODE_CUSTOM) {
            $this->mode = self::MODE_AND;
        }

        $numberOfFilters = count($this->filters->enabled());
        $queryParts = [];
        for ($i = 1; $i <= $numberOfFilters; $i++) {
            $queryParts[] = "({{$i}})";
        }

        return implode(" {$this->mode} ", $queryParts);
    }

    private function buildHasManyFilter(DatabaseQuery $query, DatabaseFilter $filter)
    {
        $filterTable = $filter->tableAlias();
        $relation = $this->source->relationFromTableAlias($filter->tableAlias());
        if ($relation) {
            $filterTable = $relation->table();
        }

        $hasManyQuery = $query->createForDatabase($this->source->db());

        $filterField = $this->quoteNameStr([$filterTable, $filter->field()]);

        return $hasManyQuery
            ->select('*')
            ->from($filterTable)
            ->where($filterField, $filter->operator(), $this->quote($filter->value()));
    }

    private function buildFiltersWhere(DatabaseQuery $query): string
    {
        $queryStructure = $this->buildQueryStructure();

        foreach ($this->filters->enabled() as $i => $filter) {
            // Query will be radically different based on the relation type
            $relation = $this->source->relationFromTableAlias($filter->tableAlias());
            $type = $relation ? $relation->type() : Relation::BELONGS_TO;

            // User inputs {1} or {n}
            $k = $i + 1;
            $field = $this->quoteNameStr([$filter->tableAlias(), $filter->field()]);

            // Has many, use WHERE EXISTS (subquery on the related table)
            if ($type === Relation::HAS_MANY) {
                $hasManyQuery = $this->buildHasManyFilter($query, $filter);
                $queryStructure = str_replace("{{$k}}", 'EXISTS(' . $hasManyQuery . ')', $queryStructure);

                continue;
            }

            // Belongs to, simple WHERE related_field
            $value = $filter->value() === null ? 'NULL' : $this->quote($filter->value());
            $queryStructure = str_replace("{{$k}}", "({$field} {$filter->operator()} $value)", $queryStructure);
        }

        return $queryStructure;
    }

    private function pluckMainTablePrimaryKeys(Relation $relation, array $data): array
    {
        $ids = array_filter(
            array_map(function ($row) use ($relation) {
                $alias = $this->source->table() . '_' . $this->source->primaryKey();

                return $row[$alias] ?? null;
            }, $data)
        );

        return $ids;
    }

    private function replaceBelongsToFieldWithObject(array &$data): array
    {
        $return = [];
        /** @var Relation $relation */
        foreach ($this->source->belongsToRelations() as $relation) {
            $return[$relation->relationFieldName()] = [];
            foreach ($data as $key => $value) {
                if ($relation->tableAlias() && stripos($key, $relation->tableAlias()) === 0) {
                    $fieldKey = substr($key, strlen($relation->tableAlias()) + 1);
                    $return[$relation->relationFieldName()][self::encodeField($fieldKey)] = $value;
                    unset($data[$key]);
                }
            }
        }

        return $return;
    }

    private function removeTablePrefixFromData(array $data, string $table, array $return = []): array
    {
        foreach ($data as $key => $value) {
            // remove the prefixed table name added previously
            $key = str_replace($table . '_', '', $key);
            $return[$key] ??= $value;
        }

        return $return;
    }

    private function addOrdering(DatabaseQuery $query): DatabaseQuery
    {
        if ($this->randomOrdering) {
            return $query->orderBy('RAND()');
        }

        foreach ($this->orders->enabled() as $order) {
            $query = $order->apply($query);
        }

        return $query;
    }

    private function selectFields(array $tables): array
    {
        if ($this->count) {
            $alias = $this->quoteNameStr(['recordsCount']);

            return ['COUNT(*) as ' . $alias];
        }

        $fields = [];
        foreach ($tables as $tblAlias => $tbl) {
            $tableFields = $this->aliasedFieldsForTable($tbl, $tblAlias);
            $fields = array_merge($fields, $tableFields);
        }

        return $fields;
    }
}
