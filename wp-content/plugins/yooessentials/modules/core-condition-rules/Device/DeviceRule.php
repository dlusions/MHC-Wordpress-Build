<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Device;

use ZOOlanders\YOOessentials\Condition\MobileDetect;
use ZOOlanders\YOOessentials\Condition\ConditionRule;

class DeviceRule extends ConditionRule
{
    public function resolve($props, $node): bool
    {
        if (!isset($props->devices)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        return in_array($this->getDevice(), $props->devices);
    }

    protected function getDevice(): string
    {
        static $device = null;

        if (!$device) {
            $detect = new MobileDetect();

            switch (true) {
                case $detect->isTablet():
                    $device = 'tablet';

                    break;

                case $detect->isMobile():
                    $device = 'mobile';

                    break;

                default:
                    $device = 'desktop';
            }
        }

        return $device;
    }
}
