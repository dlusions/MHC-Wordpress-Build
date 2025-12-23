<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Airtable\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Airtable\AirtableSource;

class AirtableRecordsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    private AirtableRecordType $recordType;

    public const NAME = 'records';
    public const LABEL = 'Records';
    public const DESCRIPTION = 'List of records';

    public function __construct(SourceInterface $source, AirtableRecordType $recordType)
    {
        parent::__construct($source);

        $this->recordType = $recordType;
    }

    public static function getCacheKey(): string
    {
        return 'airtable-records-query';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => ['listOf' => $this->recordType->name()],

                    'args' => [
                        'view' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'maxRecords' => [
                            'type' => 'Int',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            'view' => [
                                'label' => 'View',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'Optionally choose an Airtable View which to query with.',
                                'endpoint' => 'yooessentials/source/airtable/views',
                                'meta' => false,
                                'params' => [
                                    'source_id' => $this->source->id(),
                                ],
                            ],
                            '_offset_limit' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the starting point and limit the number of records.',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'modifier' => 1,
                                        'default' => AirtableSource::RECORDS_OFFSET,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 0,
                                            'placeholder' => AirtableSource::RECORDS_OFFSET,
                                        ],
                                    ],
                                    'maxRecords' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => AirtableSource::RECORDS_LIMIT,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'placeholder' => AirtableSource::RECORDS_LIMIT,
                                        ],
                                    ],
                                ],
                            ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        /** @var AirtableSource */
        $source = self::loadSource($args, AirtableSource::class);

        if (!$source) {
            return [];
        }

        $result = self::resolveFromCache($source, $args, fn () => $source->api()->listRecords($source->base, $source->table, $args));

        return $result;
    }
}
