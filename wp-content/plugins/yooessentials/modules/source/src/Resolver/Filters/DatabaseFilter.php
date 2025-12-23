<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Filters;

use ZOOlanders\YOOessentials\Source\Provider\Database\DatabaseSource;

class DatabaseFilter extends Filter
{
    public const DEFAULT_OPERATOR = DatabaseOperators::EQUAL;
    public const OPERATORS = [
        'Equal to' => DatabaseOperators::EQUAL,
        'Not equal to' => DatabaseOperators::NOT_EQUAL,
        'Less than' => DatabaseOperators::LESS,
        'Greater than' => DatabaseOperators::GREATER,
        'Less than or equal to' => DatabaseOperators::LESS_OR_EQUAL,
        'Greater than or equal to' => DatabaseOperators::GREATER_OR_EQUAL,
        'Starts with' => DatabaseOperators::STARTS_WITH,
        'Ends with' => DatabaseOperators::ENDS_WITH,
        'Starts not with' => DatabaseOperators::STARTS_NOT_WITH,
        'Ends not with' => DatabaseOperators::ENDS_NOT_WITH,
        'LIKE' => DatabaseOperators::LIKE,
        'LIKE %%' => DatabaseOperators::CONTAINS,
        'Is Null' => DatabaseOperators::NULL,
        'Is not Null' => DatabaseOperators::NOT_NULL,
    ];

    protected DatabaseSource $source;

    public function __construct(array $config, DatabaseSource $source)
    {
        parent::__construct($config);

        $this->source = $source;
    }

    public static function operators(): array
    {
        return self::OPERATORS;
    }

    public static function defaultOperator(): string
    {
        return self::DEFAULT_OPERATOR;
    }

    public function tableAlias(): string
    {
        return $this->config('relation', $this->source->table());
    }

    public function operator(): string
    {
        $operator = $this->config('operator', static::defaultOperator());
        if (!in_array($operator, array_values(static::operators()))) {
            $operator = self::DEFAULT_OPERATOR;
        }

        switch ($operator) {
            case DatabaseOperators::NULL:
                return 'IS';
            case DatabaseOperators::NOT_NULL:
                return 'IS NOT';
            case DatabaseOperators::CONTAINS:
            case DatabaseOperators::ENDS_WITH:
            case DatabaseOperators::STARTS_WITH:
                return 'LIKE';
            case DatabaseOperators::ENDS_NOT_WITH:
            case DatabaseOperators::STARTS_NOT_WITH:
                return 'NOT LIKE';
            default:
                return $operator;
        }
    }

    public function value()
    {
        $value = $this->config('value');
        $operator = $this->config('operator');

        switch ($operator) {
            case DatabaseOperators::NULL:
            case DatabaseOperators::NOT_NULL:
                return null;
            case DatabaseOperators::CONTAINS:
                return "%{$value}%";
            case DatabaseOperators::STARTS_WITH:
            case DatabaseOperators::STARTS_NOT_WITH:
                return "{$value}%";
            case DatabaseOperators::ENDS_WITH:
            case DatabaseOperators::ENDS_NOT_WITH:
                return "%{$value}";
            default:
                return $value;
        }
    }

    public function validate(): void
    {
        if (strlen($this->field()) <= 0) {
            throw InvalidFilterException::create('Field is required', $this->config());
        }

        if (strlen($this->operator()) <= 0) {
            throw InvalidFilterException::create('Operator is required', $this->config());
        }

        if (in_array($this->config('operator'), ['null', '!null'])) {
            return;
        }

        if ($this->value() === null) {
            throw InvalidFilterException::create('Value is required for this operator', $this->config());
        }
    }
}
