<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage;

use ZOOlanders\YOOessentials\Migration;

return [
    'routes' => [['post', StorageAdapterController::PRESAVE_ENDPOINT, StorageAdapterController::class . '@presave']],

    'events' => [
        'customizer.init' => [
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'theme.init' => [
            Listener\RegisterStorages::class => ['@handle', -10],
        ],

        'yooessentials.config.load' => [
            Listener\ValidateStorages::class => ['@load'],
        ],
    ],

    'loaders' => [
        'yooessentials-storages' => new StorageAdapterLoader(),
    ],

    'services' => [
        StorageService::class => '',
    ],

    'yooessentials-migrations' => [
        Migration\Migration220\RemoveDuplicatedStorages::class
    ],
];
