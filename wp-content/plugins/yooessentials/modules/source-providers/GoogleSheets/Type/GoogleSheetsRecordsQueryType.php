<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleSheets\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\GoogleSheets\GoogleSheetsSource;

class GoogleSheetsRecordsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasDynamicFields;

    protected SourceInterface $source;

    private GoogleSheetsSheetType $sheetType;

    public const NAME = 'records';
    public const LABEL = 'Records';
    public const DESCRIPTION = 'List of records';

    public function __construct(SourceInterface $source, GoogleSheetsSheetType $sheetType)
    {
        parent::__construct($source);

        $this->sheetType = $sheetType;
    }

    public static function getCacheKey(): string
    {
        return 'google-sheets-query';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => ['listOf' => $this->sheetType->name()],

                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
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
                            '_offset_limit' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the starting point and limit the number of rows.',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'modifier' => 1,
                                        'default' => GoogleSheetsSource::SHEET_ROW_OFFSET,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 0,
                                            'placeholder' => GoogleSheetsSource::SHEET_ROW_OFFSET,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => GoogleSheetsSource::SHEET_ROW_LIMIT,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'placeholder' => GoogleSheetsSource::SHEET_ROW_LIMIT,
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
        /** @var GoogleSheetsSource */
        $source = self::loadSource($args, GoogleSheetsSource::class);

        if (!$source) {
            return [];
        }

        $result = self::resolveFromCache($source, $args, function () use ($source, $args) {
            $headers = $source->headers();
            $range = self::getRange($args, $source);
            $values = $source->api()->values($source->spreadsheet, $range)['values'] ?? [];

            $data = [];
            foreach ($values as $row) {
                $rowData = [];
                foreach ($row as $k => $value) {
                    $header = $headers[$k] ?? null;

                    if ($header) {
                        $rowData[self::encodeField($header)] = $value;
                    }
                }

                $data[] = $rowData;
            }

            return $data;
        });

        return $result;
    }

    protected static function getRange(array $args, GoogleSheetsSource $source): string
    {
        $offset = $args['offset'] ?? GoogleSheetsSource::SHEET_ROW_OFFSET;
        $limit = $args['limit'] ?? GoogleSheetsSource::SHEET_ROW_LIMIT;

        if ($offset < 0) {
            $offset = 0;
        }

        // force a limit
        if ($limit <= 0) {
            $limit = 1000;
        }

        // skip the header, and sheets starts from 1
        $offset += 2;
        $limit += $offset - 1;

        return $source->formatRange($offset, $limit);
    }
}
