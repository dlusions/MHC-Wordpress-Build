<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Calendar;

use YOOtheme\Arr;

class WeekRule extends DayRule
{
    public function resolveProps(object $props, object $node): object
    {
        if (!isset($props->weeks)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        $props->weeks = static::parseWeeks($props->weeks);

        return $props;
    }

    public function resolve($props, $node): bool
    {
        $currentWeek = (int) $this->now()->format('W');

        return in_array($currentWeek, $props->weeks, true);
    }

    protected static function parseWeeks($weeks): array
    {
        if (is_string($weeks)) {
            $weeks = self::parseTextareaList($weeks);
        }

        // expand ranges
        foreach ($weeks as $i => $value) {
            if (str_contains($value, '-')) {
                $weeks[$i] = range(...explode('-', $value));
            }
        }

        // flatten and map to integer
        $weeks = array_map('intval', Arr::flatten($weeks));

        return $weeks;
    }
}
