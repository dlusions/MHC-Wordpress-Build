<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Cloudflare;

use ZOOlanders\YOOessentials\Auth\AuthDriver;

return [
    'routes' => [
        [
            'post',
            CloudflareAuthDriverController::PRESAVE_API_TOKEN_ENDPOINT,
            CloudflareAuthDriverController::class . '@presave',
        ],
    ],

    'yooessentials-auth-drivers' => [
        AuthDriver::class => [__DIR__ . '/driver-api-token.json', __DIR__ . '/driver-stream-key.json'],
    ],
];
