<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Airtable\Type;

use ZOOlanders\YOOessentials\Source\Provider\Airtable\AirtableSource;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class AirtableRecordQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'record';
    public const LABEL = 'Record';
    public const DESCRIPTION = 'Single record';

    private AirtableRecordType $recordType;

    public function __construct(SourceInterface $source, AirtableRecordType $recordType)
    {
        parent::__construct($source);

        $this->recordType = $recordType;
    }

    public static function getCacheKey(): string
    {
        return 'airtable-record-query';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => $this->recordType->name(),

                    'args' => [
                        'recordId' => [
                            'type' => 'String',
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
                            'recordId' => [
                                'label' => 'Record ID',
                                'description' => 'The record ID which to query.',
                                'source' => true,
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

    public static function resolve($root, array $args): array
    {
        /** @var AirtableSource */
        $source = self::loadSource($args, AirtableSource::class);

        $recordId = $args['recordId'] ?? null;

        if (!$source || !$recordId) {
            return [];
        }

        $result = self::resolveFromCache($source, $args, fn () => $source->api()->getRecord($source->base, $source->table, $recordId));

        return $result;
    }
}
