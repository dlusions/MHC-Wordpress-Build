<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;

class MainQueryType extends AbstractQueryType
{
    public function name(): string
    {
        return Str::camelCase([$this->source->queryName(), 'Query'], true);
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->source->queryName() => [
                    'type' => $this->name(),
                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->source->name(),
                        'description' => $this->source->description(),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ]
        ];
    }

    public static function resolve($root)
    {
        return $root;
    }
}
