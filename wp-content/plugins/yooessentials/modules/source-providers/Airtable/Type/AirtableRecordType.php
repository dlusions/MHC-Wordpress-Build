<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Airtable\Type;

use YOOtheme\Str;
use YOOtheme\GraphQL\Type\Definition\ResolveInfo;
use ZOOlanders\YOOessentials\Api\Airtable\AirtableApi;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSource;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSourceInterface;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class AirtableRecordType extends GenericType implements HasSourceInterface
{
    use HasSource;

    public const LABEL = 'Record';

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
    }

    public function name(): string
    {
        return Str::camelCase([$this->source->queryName(), 'Record'], true);
    }

    public function config(): array
    {
        try {
            $tableFields = $this->source->getTable('fields') ?? [];
            $fields = $this->parseTableFields($tableFields);
        } catch (\Throwable $e) {
            $fields = [];
        }

        return [
            'fields' => $fields,
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    protected function parseTableFields(array $tableFields): array
    {
        $fields = [];

        foreach ($tableFields as $field) {
            $field = (object) $field;

            switch (AirtableApi::castType($field->type)) {
                case 'text':
                    $fields[$field->id] = [
                        'type' => 'String',
                        'metadata' => [
                            'label' => $field->name,
                            'filters' => ['limit'],
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::resolveField',
                        ],
                    ];

                    break;

                case 'number':
                    $fields[$field->id] = [
                        'type' => 'Int',
                        'metadata' => [
                            'label' => $field->name,
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::resolveField',
                        ],
                    ];

                    break;

                case 'date':
                    $fields[$field->id] = [
                        'type' => 'String',
                        'metadata' => [
                            'label' => $field->name,
                            'filters' => ['date'],
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::resolveField',
                        ],
                    ];

                    break;

                case 'multi':

                    if ($field->type === 'multipleSelects') {
                        $fields[$field->id] = [
                            'type' => 'String',
                            'args' => [
                                'separator' => [
                                    'type' => 'String',
                                ],
                            ],
                            'metadata' => [
                                'label' => $field->name,
                                'arguments' => [
                                    'separator' => [
                                        'label' => 'Separator',
                                        'description' => 'Set the separator between values.',
                                        'default' => ', ',
                                    ],
                                ],
                            ],
                            'extensions' => [
                                'call' => __CLASS__ . '::resolveMultipleField',
                            ],
                        ];
                    }

                    if ($field->type === 'multipleAttachments') {
                        $fields["{$field->id}_mono"] = [
                            'type' => AirtableAttachmentType::NAME,
                            'metadata' => [
                                'label' => $field->name,
                            ],
                            'extensions' => [
                                'call' => __CLASS__ . '::resolveMonoField',
                            ],
                        ];

                        $fields[$field->id] = [
                            'type' => [
                                'listOf' => AirtableAttachmentType::NAME
                            ],
                            'metadata' => [
                                'label' => "{$field->name} (Attachment)",
                            ],
                            'extensions' => [
                                'call' => __CLASS__ . '::resolveField',
                            ],
                        ];
                    }

                    if ($field->type === 'multipleCollaborators') {
                        $fields[$field->id] = [
                            'type' => [
                                'listOf' => AirtableUserType::NAME
                            ],
                            'metadata' => [
                                'label' => "{$field->name} (User)",
                            ],
                            'extensions' => [
                                'call' => __CLASS__ . '::resolveField',
                            ],
                        ];
                    }

                    break;

                case 'user':
                    $fields[$field->id] = [
                        'type' => AirtableUserType::NAME,
                        'metadata' => [
                            'label' => $field->name,
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::resolveField',
                        ],
                    ];

                    break;

                default:
                    $fields[$field->id] = [
                        'type' => 'String',
                        'metadata' => [
                            'label' => $field->name,
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::resolveField',
                        ]
                    ];

                    break;
            }
        }

        $fields['createdTime'] = [
            'type' => 'String',
            'metadata' => [
                'label' => 'Created Time',
                'filters' => ['date'],
            ],
        ];

        $fields['id'] = [
            'type' => 'String',
            'metadata' => [
                'label' => 'ID',
            ],
        ];

        return $fields;
    }

    public static function resolveField(
        array $record,
        $args = [],
        $context = '',
        ?ResolveInfo $info = null
    ) {
        return $record['fields'][$info->fieldName] ?? null;
    }

    public static function resolveMonoField(
        array $record,
        $args = [],
        $context = '',
        ?ResolveInfo $info = null
    ) {
        $fieldName = str_replace('_mono', '', $info->fieldName);

        return $record['fields'][$fieldName][0] ?? null;
    }

    public static function resolveMultipleField(
        array $record,
        $args = [],
        $context = '',
        ?ResolveInfo $info = null
    ): string {
        $separator = $args['separator'] ?? ', ';
        $value = $record['fields'][$info->fieldName] ?? [];

        return implode($separator, $value);
    }
}
