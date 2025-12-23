<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

use YOOtheme\Path;
use ZOOlanders\YOOessentials\Vendor\Symfony\Component\Cache\Adapter\FilesystemAdapter;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;

return [
    'config' => [
        'yooessentials' => [
            'version' => '2.4.12',
            'build' => '1202.1125',
        ]
    ],

    'routes' => [
        ['get', CoreController::GET_CHANGELOG_URL, CoreController::class . '@getChangelog'],
    ],

    'events' => [
        'customizer.init' => [
            Listener\LoadCustomizerData::class => [
                ['@loadScript'], // before core assets
                ['@loadAssets', 15] // before other addons
            ],
        ],

        'metadata.load' => [
            Listener\LoadVersion::class => ['@handle', -10]
        ],
    ],

    'loaders' => [
        'yooessentials-bootstrap' => new BootstrapsLoader(),
    ],

    'services' => [
        CacheInterface::class => fn () => new FilesystemAdapter('yooessentials', 0, Path::resolve('~theme/cache/')),
    ],
];
