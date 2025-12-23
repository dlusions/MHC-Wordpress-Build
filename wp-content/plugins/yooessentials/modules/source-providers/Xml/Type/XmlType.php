<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSource;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSourceInterface;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class XmlType extends GenericType implements HasSourceInterface
{
    use ExtractsFields, HasSource;

    private array $dataTypes;

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
        $this->dataTypes = $this->source->dataTypes();
        $this->getFields($this->source->xml()->toArray());
    }

    public function name(): string
    {
        return Str::camelCase([$this->source->queryName()], true);
    }

    public function config(): array
    {
        $data = $this->source->xml()->toArray();

        $fields = $this->getFields($data);

        return [
            'fields' => $fields,
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public function fieldsMetadata(): array
    {
        $typeFields = [];
        foreach ($this->source()->types() as $type) {
            $typeFields[$type->name()] = $type->config()['fields'] ?? [];
        }

        $mainFields = $this->config()['fields'] ?? [];

        return  array_merge([], $this->parseFields($mainFields, $typeFields));
    }

    private function parseFields(array $mainFields, array $typeFields, string $prefix = ''): array
    {
        $fields = [];

        foreach ($mainFields as $key => $field) {
            $type = $field['type'];

            if (!is_string($type)) {
                $type = $type['listOf'] ?? null;
            }

            if (isset($typeFields[$type])) {
                $fields = array_merge($fields, $this->parseFields($typeFields[$type], $typeFields, $prefix . $key . '.'));

                continue;
            }

            $fields[] = [
                'value' => $prefix . $key,
                'text' => $field['metadata']['label'] ?? $key,
                'meta' => $prefix . $key,
            ];
        }

        return $fields;
    }
}
