<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Csv\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSource;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSourceInterface;
use ZOOlanders\YOOessentials\Source\HasDynamicFields;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class CsvFileType extends GenericType implements HasSourceInterface
{
    use HasDynamicFields, HasSource;

    public const LABEL = 'Record';

    public function name(): string
    {
        return Str::camelCase([$this->source->queryName(), 'Record'], true);
    }

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
    }

    public function config(): array
    {
        $fields = [];

        foreach ($this->source->csv()->getHeader() as $header) {
            $fields[self::encodeField($header)] = [
                'type' => 'String',
                'metadata' => [
                    'label' => self::labelField($header),
                    'fields' => [],
                ],
            ];
        }

        return [
            'fields' => $fields,
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }
}
