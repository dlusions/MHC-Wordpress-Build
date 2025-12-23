<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Dynamic;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Condition\ConditionRule;

class DynamicRule extends ConditionRule
{
    public const EMPTY = '!';
    public const EQUALS = '=';
    public const INCLUDES = '~=';
    public const STARTS_WITH = '^=';
    public const ENDS_WITH = '$=';
    public const LESSER = '<';
    public const GREATER = '>';

    public function resolve($props, $node): bool
    {
        if (!isset($props->value, $props->condition)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        $value = $props->value;
        $condition = $props->condition;
        $conditionValue = $props->condition_value ?? '';
        $caseInsensitive = $props->case_insensitive ?? false;

        switch ($condition) {
            case self::EMPTY:
                return self::isEmpty($value);
            case self::INCLUDES:
                return self::doesInclude($value, $conditionValue, $caseInsensitive);
            case self::EQUALS:
                return self::equals($value, $conditionValue, $caseInsensitive);
            case self::LESSER:
            case self::GREATER:
                return self::lesserOrGreaterThan($value, $condition, $conditionValue);
            case self::STARTS_WITH:
            case self::ENDS_WITH:
                return self::startsOrEndsWith($value, $condition, $conditionValue, $caseInsensitive);
        }

        return false;
    }

    protected static function isEmpty($value): bool
    {
        return empty(is_string($value) ? trim($value) : $value);
    }

    protected static function doesInclude($value, $conditionValue, bool $caseInsensitive): bool
    {
        if (is_array($value)) {
            return self::includes($value, (array) $conditionValue, $caseInsensitive);
        }

        if (is_array($conditionValue)) {
            return false;
        }

        $value = self::toString($value, $caseInsensitive);
        $conditionValue = self::toString($conditionValue, $caseInsensitive);

        return str_contains($value, $conditionValue);
    }

    protected static function equals($value, $val, bool $caseInsensitive): bool
    {
        $valueDate = self::createDate($value);
        $valDate = self::createDate($val);

        if ($valueDate || $valDate) {
            if ($valueDate && $valDate) {
                return (int) $valueDate->format('U') === (int) $valDate->format('U');
            }

            return false;
        }

        $value = (array) $value;
        $val = (array) $val;

        return count($value) === count($val) && self::includes($value, $val, $caseInsensitive);
    }

    protected static function lesserOrGreaterThan($value, $condition, $conditionValue): bool
    {
        // enforce as int if string is numerical
        $value = is_string($value) && ctype_digit($value) ? (int) $value : $value;
        $conditionValue = is_string($conditionValue) && ctype_digit($conditionValue) ? (int) $conditionValue : $conditionValue;

        $valueDate = self::createDate($value);
        $conditionValueDate = self::createDate($conditionValue);

        if ($valueDate || $conditionValueDate) {
            if ($valueDate && $conditionValueDate) {
                $valueDate = (int) $valueDate->format('U');
                $conditionValueDate = (int) $conditionValueDate->format('U');

                return $condition === self::LESSER
                    ? $valueDate < $conditionValueDate
                    : $valueDate > $conditionValueDate;
            }

            return false;
        }

        $value = (array) $value;
        $conditionValue = (array) $conditionValue;

        return $condition === self::LESSER
            ? $value < $conditionValue
            : $value > $conditionValue;
    }

    protected static function startsOrEndsWith($value, $condition, $conditionValue, bool $caseInsensitive): bool
    {
        $value = self::normalize($value, $caseInsensitive);
        $conditionValue = self::normalize($conditionValue, $caseInsensitive);

        $value = $value[0] ?? '';
        $conditionValue = $conditionValue[0] ?? '';

        return $condition === self::STARTS_WITH
            ? str_starts_with($value, $conditionValue)
            : str_ends_with($value, $conditionValue);
    }

    protected static function includes(array $value, array $conditionValue, bool $caseInsensitive): bool
    {
        if ($caseInsensitive) {
            $value = array_map('strtolower', $value);
            $conditionValue = array_map('strtolower', $conditionValue);
        }

        return count(array_diff($conditionValue, $value)) === 0;
    }

    protected static function toString($value, $caseInsensitive)
    {
        if (is_scalar($value) || is_callable([$value, '__toString'])) {
            $value = (string) $value;

            return $caseInsensitive ? strtolower($value) : $value;
        }

        return '';
    }

    protected static function normalize($value, bool $caseInsensitive): array
    {
        $value = (array) $value;

        if ($caseInsensitive) {
            $value = array_map('strtolower', $value);
        }

        return $value;
    }

    protected static function createDate($date): ?\DateTime
    {
        if (!is_string($date) || strlen($date) === 1) {
            return null;
        }

        try {
            $tz = new \DateTimeZone(app()->config->get('yooessentials.timezone') ?? 'UTC');

            return (new \DateTime($date))->setTimezone($tz);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
