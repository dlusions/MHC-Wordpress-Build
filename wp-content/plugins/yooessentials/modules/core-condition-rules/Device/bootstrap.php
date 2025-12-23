<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Device;

return [
    'yooessentials-condition-rules' => [
        DeviceRule::class => __DIR__ . '/config/device.json',
        BrowserRule::class => __DIR__ . '/config/browser.json',
        OpSysRule::class => __DIR__ . '/config/opsys.json',
    ],
];
