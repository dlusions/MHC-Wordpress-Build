<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssAuthorType;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssEnclosureType;
use ZOOlanders\YOOessentials\Source\Provider\Rss\Type\RssLinkType;
use ZOOlanders\YOOessentials\Util\Arr;

trait ExtractsFields
{
    use HasDynamicFields;

    protected array $types = [];

    protected ?array $fields = null;
    public function types(): array
    {
        return $this->types;
    }

    private function getFields(array $data, string $prefix = ''): array
    {
        if ($this->fields !== null) {
            return $this->fields;
        }

        $fields = [];

        foreach ($data as $header => $field) {
            if (is_numeric($header)) {
                $fields = [];
            } else {
                $fields[self::encodeField($header)] = $this->mapField($header, $field, $prefix);
            }
        }

        return $this->fields = $fields;
    }

    private function mapField(string $header, $field, string $prefix = ''): array
    {
        $headerKey = self::encodeField($header);
        $key = $prefix ? $prefix . '.' . $headerKey : $headerKey;
        $dataType = $this->dataTypes[$key]['type'] ?? null;
        $dataTypeOptions = $this->dataTypes[$key]['options'] ?? [];

        if ($field instanceof \DateTimeInterface) {
            return $this->dateField($header, $dataTypeOptions);
        }

        if (!is_array($field) || empty($field)) {
            return $this->mapFieldWithDataType($dataType, $header, $dataTypeOptions);
        }

        // Associative array
        if (Arr::hasStringKeys($field)) {
            return $this->arrayField($header, $field, $key);
        }

        // Array of arrays
        $firstField = $field[array_keys($field)[0]];
        if (is_array($firstField)) {
            return $this->listArrayField($header, $field, $key);
        }

        // List of raw data, let's do a list of given dataTypes (or strings)
        return $this->mapFieldWithDataType($dataType, $header, $dataTypeOptions);
    }

    private function arrayField(string $header, array $field, string $prefix): array
    {
        $type = new XmlArrayType($header, $field, $this->name(), $this->dataTypes ?? [], $prefix);

        $this->types = array_merge($this->types, $type->types());
        $this->types[] = $type;

        return [
            'type' => $type->name(),
            'metadata' => [
                'label' => $header . ' Items',
                'fields' => [],
            ],
        ];
    }

    private function listArrayField(string $header, array $field, string $prefix): array
    {
        $type = new XmlArrayType($header, $field[0] ?? $field, $this->name(), $this->dataTypes ?? [], $prefix);

        $this->types = array_merge($this->types, $type->types());
        $this->types[] = $type;

        return [
            'type' => ['listOf' => $type->name()],
            'metadata' => [
                'label' => self::prepareLabel($header),
                'fields' => [],
            ],
        ];
    }

    private function imageField(string $header): array
    {
        return [
            'type' => XmlImageType::NAME,
            'metadata' => [
                'label' => self::prepareLabel($header),
                'fields' => [],
            ],
        ];
    }

    private function imageUrlField(string $header): array
    {
        return [
            'type' => 'String',
            'metadata' => [
                'label' => self::prepareLabel($header),
                'fields' => [],
            ],
            'extensions' => [
                'call' => XmlImageType::class . '::url',
            ],
        ];
    }

    private function urlField(string $header): array
    {
        return [
            'type' => 'String',
            'metadata' => [
                'label' => self::prepareLabel($header),
                'fields' => [],
            ],
            'extensions' => [
                'call' => __CLASS__ . '::url',
            ],
        ];
    }

    private function linkField(string $header): array
    {
        return [
            'type' => RssLinkType::NAME,
            'metadata' => [
                'label' => self::prepareLabel($header),
                'fields' => [],
            ],
            'extensions' => [
                'call' => [
                    'func' => RssLinkType::class . '::resolve',
                    'args' => [
                        'header' => $header,
                    ],
                ],
            ],
        ];
    }

    private function authorField(string $header): array
    {
        return [
            'type' => RssAuthorType::NAME,
            'metadata' => [
                'label' => self::prepareLabel($header),
                'fields' => [],
            ],
        ];
    }

    private function enclosureField(string $header): array
    {
        return [
            'type' => RssEnclosureType::NAME,
            'metadata' => [
                'label' => self::prepareLabel($header),
                'fields' => [],
            ],
            'extensions' => [
                'call' => [
                    'func' => RssEnclosureType::class . '::resolve',
                    'args' => [
                        'header' => $header,
                    ],
                ],
            ],
        ];
    }

    public static function prepareLabel(string $header): string
    {
        $header = str_replace(':', ' - ', $header);

        return Str::titleCase($header);
    }

    public static function resolveDateTime($data, $args)
    {
        $date = $data[$args['header']] ?? null;
        if ($date === null) {
            return null;
        }

        if (!$date instanceof \DateTimeInterface) {
            $parsedDate = \DateTime::createFromFormat($args['options']['date_format'] ?? 'U', $date);
            if ($parsedDate) {
                $date = $parsedDate;
            }
        }

        return $date->format('U');
    }

    private function dateField(string $header, array $options): array
    {
        return [
            'type' => 'String',
            'metadata' => [
                'label' => self::prepareLabel($header),
                'filters' => ['date'],
            ],
            'extensions' => [
                'call' => [
                    'func' => XmlType::class . '::resolveDateTime',
                    'args' => [
                        'header' => $header,
                        'options' => $options,
                    ],
                ],
            ],
        ];
    }

    private function mapFieldWithDataType($dataType, string $header, array $options): array
    {
        switch ($dataType) {
            case 'Date':
                return $this->dateField($header, $options);
            case 'Image':
                return $this->imageUrlField($header);
            case 'Url':
                return $this->urlField($header);
            case 'Int':
                return [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => self::prepareLabel($header),
                        'fields' => [],
                    ],
                ];
            default:
                return [
                    'type' => $dataType ?? 'String',
                    'metadata' => [
                        'label' => self::prepareLabel($header),
                        'fields' => [],
                        'filters' => ['limit'],
                    ],
                ];
        }
    }
}
