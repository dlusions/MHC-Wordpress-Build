<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Aws;

return [
    'routes' => [['post', AwsAuthDriverController::PRE_SAVE_ENDPOINT, AwsAuthDriverController::class . '@presave']],

    'yooessentials-auth-drivers' => [
        AwsAuthDriver::class => __DIR__ . '/driver.json',
    ],
];
