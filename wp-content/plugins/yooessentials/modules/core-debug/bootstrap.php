<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Debug;

return [
    'routes' => [
        ['get', DebugController::DOWNLOAD_DEBUG_DATA_URL, DebugController::class . '@downloadDebugData', ['allowed' => true]],
    ],

    'events' => [
        'customizer.init' => [
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'yooessentials.info' => [
            ConsoleLogger::class => ['info', -10],
        ],

        'yooessentials.warn' => [
            ConsoleLogger::class => ['warn', -10],
        ],

        'yooessentials.error' => [
            ConsoleLogger::class => ['error', -10],
        ],
    ],
];
