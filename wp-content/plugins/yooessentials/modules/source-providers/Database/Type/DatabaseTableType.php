<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Type;

use YOOtheme\Str;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSource;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSourceInterface;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Database\DatabaseSource;
use ZOOlanders\YOOessentials\Source\Provider\Database\Table\Relation;

class DatabaseTableType extends GenericType implements HasSourceInterface
{
    use HasDynamicFields, HasSource;

    public const LABEL = 'Record';

    protected ?Relation $relation;

    /** @var DatabaseSource */
    protected SourceInterface $source;

    protected $columns = [];

    public function __construct(SourceInterface $source, ?Relation $relation = null)
    {
        $this->source = $source;
        $this->relation = $relation;
    }

    public function name(): string
    {
        return Str::camelCase([
            'database',
            $this->source->id(),
            $this->replaceTablePrefix($this->currentTable()),
            'table'
        ], true);
    }

    public function label(): string
    {
        $label = self::LABEL;

        if ($this->isRelatedTable()) {
            $label = $this->relation()->name() ?: $label . ' - ' . $this->relation()->table();
        }

        return $label;
    }

    public function isRelatedTable(): bool
    {
        return (bool) $this->relation();
    }

    public function relation(): ?Relation
    {
        return $this->relation;
    }

    public function columns(): array
    {
        if ($this->columns) {
            return $this->columns;
        }

        try {
            $table = $this->currentTable();

            return $this->columns = $this->source->tableColumns($table);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'database',
                'action' => 'db-columns',
                'table' => $table,
                'source' => "{$this->source->id()} - {$this->source->name()}",
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    public function config(): array
    {
        $fields = [];

        foreach ($this->columns() as $field => $type) {
            if (!$field) {
                continue;
            }

            $field = self::encodeField($field);

            // map the single field
            $fields[$field] = [
                'type' => $this->source->manager()->convertSqlTypeToSchemaType($type),
                'metadata' => [
                    'label' => self::labelField($field),
                    'filters' => $this->source->manager()->getSchemaFiltersFromSqlType($type),
                ],
            ];
        }

        if (!$this->isRelatedTable()) {
            foreach ($this->source->hasManyRelationMap() as $relationField => $relation) {
                $relatedType = $relation->relatedType();
                $relatedTypeName = $relatedType->name();

                // Add a new field with the related object
                $fields[$relationField] = [
                    'type' => ['listOf' => $relatedTypeName],
                    'metadata' => [
                        'label' => $relatedType->label(),
                    ],
                ];
            }

            // Add belongs to relation fields
            foreach ($this->source->belongsToRelationMap() as $relationField => $relation) {
                $relatedType = $relation->relatedType();
                $relatedTypeName = $relatedType->name();

                // Add a new field with the related object
                $fields[$relation->relationFieldName()] = [
                    'type' => $relatedTypeName,
                    'metadata' => [
                        'label' => $relatedType->label(),
                    ],
                ];
            }
        }

        return [
            'fields' => $fields,
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveRelation($row, $args)
    {
        $field = $args['fieldName'] ?? 'id';

        return $row[$field] ?? [];
    }

    private function replaceTablePrefix(string $table): string
    {
        return str_replace($this->source->db()->prefix, '', $table);
    }

    public function currentTable(): string
    {
        return $this->isRelatedTable() ? $this->relation()->table() : $this->source()->table();
    }
}
