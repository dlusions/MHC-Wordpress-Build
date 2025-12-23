<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\IpAddress;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Condition\ConditionRule;
use ZOOlanders\YOOessentials\Util;

class IpAddressRule extends ConditionRule
{
    public function resolve($props, $node): bool
    {
        if (!isset($props->ips)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        $selection = $props->ips;

        if (is_string($selection)) {
            $selection = Util\Arr::explodeList($selection);
        }

        return Arr::some($selection, fn ($range) => Util\Ip::checkIP($range));
    }

    public function logArgs(object $props): array
    {
        $ip = Util\Ip::getIP();

        return [
            'IP' => $ip,
        ];
    }
}
