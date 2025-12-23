<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Datetime;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Condition\ConditionRule;

class TimeRule extends ConditionRule
{
    protected \DateTimeZone $tz;

    protected string $format = 'H:i';

    public function __construct(array $data)
    {
        $this->tz = new \DateTimeZone(app()->config->get('yooessentials.timezone') ?? 'UTC');

        parent::__construct($data);
    }

    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    public function resolveProps(object $props, object $node): object
    {
        $props->now = $this->now();
        $props->publish_up ??= null;
        $props->publish_down ??= null;

        if ($props->publish_up) {
            $props->publish_up = $this->createDate($props->publish_up);
        }

        if ($props->publish_down) {
            $props->publish_down = $this->createDate($props->publish_down);
        }

        if (!$props->publish_up && !$props->publish_down) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        return $props;
    }

    public function createDate($date): ?\DateTimeImmutable
    {
        [$hour, $min] = explode(':', $date);

        return $this->now()
            ->setTime($hour, $min)
            // enforce today
            ->setDate(date('Y'), date('m'), date('d'));
    }

    public function logArgs(object $props): array
    {
        $tz = $this->tz->getName();

        return [
            'Now' => $props->now->setTimezone($this->tz)->format('r') . " ($tz)",
            'From' => $props->publish_up ? $props->publish_up->setTimezone($this->tz)->format('r') . " ($tz)" : '',
            'Until' => $props->publish_down ? $props->publish_down->setTimezone($this->tz)->format('r') . " ($tz)" : '',
            'Evaluated From' => $props->publish_up ? $props->publish_up->format('r') . ' (UTC)' : '',
            'Evaluated Until' => $props->publish_down ? $props->publish_down->format('r') . ' (UTC)' : '',
            'Evaluated Now' => $props->now->format('r') . ' (UTC)',
        ];
    }

    public function resolve($props, $node): bool
    {
        if ($props->publish_up and (int) $props->publish_up->format('U') > (int) $props->now->format('U')) {
            return false;
        }

        if ($props->publish_down and (int) $props->publish_down->format('U') < (int) $props->now->format('U')) {
            return false;
        }

        return true;
    }
}
