<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Filters;

use ZOOlanders\YOOessentials\Source\Resolver\ExtractsRecordValue;

class InMemoryFilter extends Filter
{
    use ExtractsRecordValue;

    protected const OPERATORS = [
        'Equal to' => InMemoryOperators::EQUAL,
        'Not equal to' => InMemoryOperators::NOT_EQUAL,
        'Less than' => InMemoryOperators::LESS,
        'Greater than' => InMemoryOperators::GREATER,
        'Less than or equal to' => InMemoryOperators::LESS_OR_EQUAL,
        'Greater than or equal to' => InMemoryOperators::GREATER_OR_EQUAL,
        'Contains' => InMemoryOperators::CONTAINS,
        'Starts with' => InMemoryOperators::STARTS_WITH,
        'Ends with' => InMemoryOperators::ENDS_WITH,
        'Starts not with' => InMemoryOperators::STARTS_NOT_WITH,
        'Ends not with' => InMemoryOperators::ENDS_NOT_WITH,
        'Is empty' => InMemoryOperators::EMPTY,
        'Is not empty' => InMemoryOperators::NOT_EMPTY,
    ];

    public const DEFAULT_OPERATOR = InMemoryOperators::EQUAL;

    public static function operators(): array
    {
        return self::OPERATORS;
    }

    public static function defaultOperator(): string
    {
        return self::DEFAULT_OPERATOR;
    }

    public function __invoke(array $record): bool
    {
        return $this->apply($record);
    }

    public function apply(array $record): bool
    {
        $value = self::extractRecordValue($this->field(), $record);

        switch ($this->operator()) {
            case InMemoryOperators::EMPTY:
                return empty($value);
            case InMemoryOperators::NOT_EMPTY:
                return !empty($value);
            case InMemoryOperators::EQUAL:
                return $this->value() == $value;
            case InMemoryOperators::NOT_EQUAL:
                return $this->value() != $value;
            case InMemoryOperators::LESS:
                return $this->value() < $value;
            case InMemoryOperators::GREATER:
                return $this->value() > $value;
            case InMemoryOperators::LESS_OR_EQUAL:
                return $this->value() <= $value;
            case InMemoryOperators::GREATER_OR_EQUAL:
                return $this->value() >= $value;
            case InMemoryOperators::STARTS_WITH:
                return $this->startsWith($value);
            case InMemoryOperators::ENDS_WITH:
                return $this->endsWith($value);
            case InMemoryOperators::STARTS_NOT_WITH:
                return !$this->startsWith($value);
            case InMemoryOperators::ENDS_NOT_WITH:
                return !$this->endsWith($value);
            case InMemoryOperators::CONTAINS:
            case InMemoryOperators::LIKE:
            default:
                return stripos($value, $this->value()) !== false;
        }
    }

    private function startsWith(string $value): bool
    {
        return stripos($value, $this->value()) === 0;
    }

    private function endsWith(string $value): bool
    {
        return stripos($value, $this->value()) === strlen($value) - strlen($this->value());
    }
}
