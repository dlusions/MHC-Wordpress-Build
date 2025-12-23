<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Filters;

use ZOOlanders\YOOessentials\HasLocalConfig;

abstract class Filter
{
    use HasLocalConfig;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    abstract public static function operators(): array;

    abstract public static function defaultOperator(): string;

    public function name(): string
    {
        return $this->config('name', '');
    }

    public function enabled(): bool
    {
        return !$this->disabled();
    }

    public function disabled(): bool
    {
        return $this->config('status', '') === 'disabled';
    }

    public function field(): string
    {
        return $this->config('field', '');
    }

    public function operator(): string
    {
        $operator = $this->config('operator', static::defaultOperator());
        if (!in_array($operator, array_values(static::operators()))) {
            $operator = static::defaultOperator();
        }

        if ($operator === InMemoryOperators::CONTAINS) {
            return InMemoryOperators::LIKE;
        }

        return $operator;
    }

    public function value()
    {
        return $this->config('value');
    }

    public function validate(): void
    {
        if (strlen($this->field()) <= 0) {
            throw InvalidFilterException::create('Field is required', $this->config());
        }

        if (strlen($this->operator()) <= 0) {
            throw InvalidFilterException::create('Operator is required', $this->config());
        }

        if ($this->value() === null) {
            throw InvalidFilterException::create('Value is required', $this->config());
        }
    }
}
