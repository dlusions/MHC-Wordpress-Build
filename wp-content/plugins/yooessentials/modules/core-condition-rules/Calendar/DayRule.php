<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Calendar;

use ZOOlanders\YOOessentials\Condition\Rule\Datetime\DateRule;

class DayRule extends DateRule
{
    public function resolveProps(object $props, object $node): object
    {
        if (!isset($props->days)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        return $props;
    }

    public function resolve($props, $node): bool
    {
        $currentDay = $this->now()->format('N');

        return in_array($currentDay, (array) $props->days);
    }

    public function logArgs(object $props): array
    {
        return [];
    }
}
