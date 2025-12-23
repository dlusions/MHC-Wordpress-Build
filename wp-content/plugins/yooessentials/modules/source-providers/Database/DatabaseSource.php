<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database;

use YOOtheme\Event;
use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Database\DatabaseManager;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\Relation;
use ZOOlanders\YOOessentials\Source\Provider\Database\Type\DatabaseTableType;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Util;
use function YOOtheme\app;

class DatabaseSource extends AbstractSourceType implements HasCacheTimes
{
    public const MODE_ROW = 'row';
    public const MODE_LIST = 'list';

    protected array $relationTypes = [];

    protected ?Database $db = null;

    protected DatabaseManager $manager;

    public function __construct(array $config = [])
    {
        $this->manager = app(DatabaseManager::class);

        parent::__construct($config);
    }

    public function bind(array $config): SourceInterface
    {
        $config['db_database'] = $config['db_name'] ?? null;
        unset($config['db_name']);

        parent::bind($config);

        return $this;
    }

    public function types(): array
    {
        $types = array_values($this->relationTypes());

        if (!$this->table()) {
            return [];
        }

        $objectType = $this->mainTableType();
        $filterType = new Type\DatabaseFilterType($this);
        $orderingType = new Type\DatabaseOrderingType($this);

        $tableTypes = $types;
        $tableTypes[] = $objectType;

        foreach ($tableTypes as $type) {
            if (empty($type->columns())) {
                Event::emit('yooessentials.error', [
                    'addon' => 'source',
                    'provider' => 'database',
                    'action' => 'db-relation-config',
                    'table' => $this->table(),
                    'source' => "{$this->id()} - {$this->name()}",
                    'error' => 'Skipped loading because table columns list is empty.'
                ]);

                return [];
            }
        }

        return array_merge($types, [
            $objectType,
            $filterType,
            $orderingType,
            new Type\DatabaseTableMetaType(),
            new Type\DatabaseTableQueryType($this),
            new DynamicSourceInputType($filterType), // wrap for props
            new DynamicSourceInputType($orderingType), // wrap for props
            new Type\DatabaseRecordQueryType($this, $objectType),
            new Type\DatabaseRecordsQueryType($this, $objectType)
        ]);
    }

    public function mainTableType(): DatabaseTableType
    {
        return new DatabaseTableType($this);
    }

    public function manager(): DatabaseManager
    {
        return $this->manager;
    }

    public function table(): string
    {
        return $this->config('table', '');
    }

    public function tableColumns(?string $table = null): array
    {
        $table ??= $this->table();

        try {
            return $this->manager->getTableColumnsFromDb($this->db(), $table);
        } catch (\Throwable $exception) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'action' => 'db-relation-config',
                'args' => $table,
                'source' => "{$this->id()} - {$this->name()}",
                'error' => $exception->getMessage(),
                'exception' => $exception
            ]);

            return [];
        }
    }

    public function mode(): string
    {
        return $this->config('mode', self::MODE_LIST);
    }

    public function primaryKey(): string
    {
        return $this->config('table_primary_key', $this->config('primary_key', 'id'));
    }

    public function hasRelations(): bool
    {
        return count($this->relationsConfig()) > 0;
    }

    public function hasHasManyRelations(): bool
    {
        if (!$this->hasRelations()) {
            return false;
        }

        return count($this->hasManyRelations()) > 0;
    }

    public function hasBelongsToRelations(): bool
    {
        if (!$this->hasRelations()) {
            return false;
        }

        return count($this->belongsToRelations()) > 0;
    }

    /** @return Relation[] */
    public function hasManyRelations(): array
    {
        return array_filter($this->relations(), fn (Relation $relation) => $relation->type() === Relation::HAS_MANY);
    }

    /** @return Relation[] */
    public function belongsToRelations(): array
    {
        return array_filter($this->relations(), fn (Relation $relation) => $relation->type() === Relation::BELONGS_TO);
    }

    /** @return DatabaseTableType[] */
    public function relationTypes(): array
    {
        if (!empty($this->relationTypes)) {
            return $this->relationTypes;
        }

        foreach ($this->relations() as $relation) {
            $this->relationTypes[] = $relation->relatedType();
        }

        return $this->relationTypes;
    }

    /** @return Relation[] */
    public function belongsToRelationMap(): array
    {
        $relationMap = [];
        foreach ($this->belongsToRelations() as $relation) {
            $relationMap[$relation->relationFieldName()] = $relation;
        }

        return $relationMap;
    }

    /** @return Relation[] */
    public function hasManyRelationMap(): array
    {
        $relationMap = [];
        foreach ($this->hasManyRelations() as $relation) {
            $relationMap[$relation->relationFieldName()] = $relation;
        }

        return $relationMap;
    }

    /** @return Relation[] */
    public function relations(): array
    {
        return array_filter(
            array_map(function ($relation) {
                try {
                    return new Relation($this, $relation);
                } catch (InvalidRelationConfigException $e) {
                    Event::emit('yooessentials.error', [
                        'addon' => 'source',
                        'action' => 'db-relation-config',
                        'args' => $relation,
                        'source' => "{$this->id()} - {$this->name()}",
                        'error' => $e->getMessage(),
                        'exception' => $e
                    ]);

                    return null;
                }
            }, $this->relationsConfig())
        );
    }

    public function relationFromTableAlias(string $tableAlias): ?Relation
    {
        foreach ($this->relations() as $relation) {
            if ($relation->tableAlias() === $tableAlias) {
                return $relation;
            }
        }

        return null;
    }

    public function relationsConfig(): array
    {
        return $this->config('table_relations', []);
    }

    public function db(): Database
    {
        if ($this->db) {
            return $this->db;
        }

        $options = Util\Prop::filterByPrefix($this->config, 'db_');
        $options = array_filter($options);

        $options['external'] = $this->config('external', false);

        return $this->db = app(DatabaseManager::class)->initialize($options);
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        return 0;
    }
}
