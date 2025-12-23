<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout;

use ZOOlanders\YOOessentials\Layout\Library\LayoutLibraryController;
use ZOOlanders\YOOessentials\Migration;

return [
    'routes' => [
        ['post', LayoutController::PRESAVE_ENDPOINT, LayoutController::class . '@presave'],
        [
            'post',
            LayoutLibraryController::LAYOUT_LIBRARY_INDEX_ENDPOINT,
            LayoutLibraryController::class . '@getLibraryIndex',
        ],
        ['post', LayoutLibraryController::LAYOUT_LIBRARY_NODE_GET_ENDPOINT, LayoutLibraryController::class . '@getNode'],
        ['post', LayoutLibraryController::LAYOUT_LIBRARY_NODE_SAVE_ENDPOINT, LayoutLibraryController::class . '@saveNode'],
        [
            'post',
            LayoutLibraryController::LAYOUT_LIBRARY_NODE_DELETE_ENDPOINT,
            LayoutLibraryController::class . '@deleteNodes',
        ],
    ],

    'events' => [
        'customizer.init' => [
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'theme.init' => [
            Listener\InitLibraries::class => ['@handle', -10],
        ],

        'yooessentials.config.load' => [
            Listener\ValidateLibraries::class => ['@load'],
        ],
    ],

    'loaders' => [
        'yooessentials-layout-libraries' => new LayoutLoader(),
    ],

    'yooessentials-layout-libraries' => [],

    'services' => [
        LayoutManager::class => '',
    ],

    'yooessentials-migrations' => [
        Migration\Migration220\RemoveDuplicateLibraries::class
    ],
];
