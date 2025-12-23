<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Mailchimp;

use ZOOlanders\YOOessentials\Auth\AuthDriver;

return [

    'routes' => [
        ['post', MailchimpAuthDriverController::PRE_OAUTH_SAVE_ENDPOINT, MailchimpAuthDriverController::class . '@presaveOauth'],
        ['post', MailchimpAuthDriverController::PRE_APIKEY_SAVE_ENDPOINT, MailchimpAuthDriverController::class . '@presaveApikey'],
    ],

    'yooessentials-auth-drivers' => [
        AuthDriver::class => [__DIR__ . '/driver-oauth.json', __DIR__ . '/driver-apikey.json']
    ],

];
