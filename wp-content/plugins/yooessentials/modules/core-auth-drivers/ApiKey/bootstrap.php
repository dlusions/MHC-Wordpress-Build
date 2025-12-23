<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\ApiKey;

use ZOOlanders\YOOessentials\Auth\AuthDriver;

return [
    'routes' => [['post', ApiKeyController::PRESAVE_ENDPOINT, ApiKeyController::class . '@presave']],

    'yooessentials-auth-drivers' => [
        AuthDriver::class => __DIR__ . '/driver.json',
    ],
];
