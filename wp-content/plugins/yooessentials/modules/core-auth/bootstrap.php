<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth;

use ZOOlanders\YOOessentials\Migration;

return [

    'routes' => [
        ['post', AuthController::GENERATE_ID_ENDPOINT, AuthController::class . '@generateId'],
        ['post', 'yooessentials/auth/presave', AuthDriverController::class . '@presaveOAuth']
    ],

    'events' => [
        'customizer.init' => [
            Listener\LoadAuths::class => ['@handle', -10],
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'yooessentials.config.save' => [
            Listener\EncryptAuths::class => ['@handle'],
        ],

        'yooessentials.config.export' => [
            Listener\DecryptAuths::class => ['@handle'],
        ],
    ],

    'services' => [
        AuthManager::class => '',
    ],

    'loaders' => [
        'yooessentials-auth-drivers' => new AuthDriverLoader(),
    ],

    'yooessentials-migrations' => [
        Migration\Migration220\RemoveDeprecatedAuth::class,
        Migration\Migration240\DecryptPublicKeys::class,
    ],
];
