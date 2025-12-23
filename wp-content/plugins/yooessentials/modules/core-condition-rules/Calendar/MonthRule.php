<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Calendar;

class MonthRule extends DayRule
{
    public function resolveProps(object $props, object $node): object
    {
        if (!isset($props->months)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        return $props;
    }

    public function resolve($props, $node): bool
    {
        $currentMonth = $this->now()->format('m');

        return in_array($currentMonth, (array) $props->months);
    }
}
