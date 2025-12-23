<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition;

use ReflectionClass;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Data;

abstract class ConditionRule extends Data implements ConditionRuleInterface
{
    public function name(): string
    {
        return $this->data['name'] ?? Str::titleCase((new ReflectionClass($this))->getShortName());
    }

    public function title(): string
    {
        return $this->data['title'] ?? Str::titleCase((new ReflectionClass($this))->getShortName());
    }

    public function group(): string
    {
        return $this->data['group'] ?? '';
    }

    public function collection(): string
    {
        return $this->data['collection'] ?? '';
    }

    public function collectionDescription(): string
    {
        return $this->data['collectionDescription'] ?? '';
    }

    public function icon(): string
    {
        return $this->data['icon'] ?? '';
    }

    public function namespace(): string
    {
        return $this->data['namespace'] ?? $this->collection();
    }

    public function description(): string
    {
        return $this->data['description'] ?? '';
    }

    public function docs(): string
    {
        return $this->data['docs'] ?? '';
    }

    public function fields(): array
    {
        return $this->data['fields'] ?? [];
    }

    public function resolveProps(object $props, object $node): object
    {
        return $props;
    }

    public function logArgs(object $props): array
    {
        return [];
    }

    protected static function parseTextareaList($content): array
    {
        if (is_string($content)) {
            return array_map(fn ($value) => trim($value), explode(',', str_replace(["\r", "\n"], ['', ','], $content)));
        }

        return $content;
    }
}
