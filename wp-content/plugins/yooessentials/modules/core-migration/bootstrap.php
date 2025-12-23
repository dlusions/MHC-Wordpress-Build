<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration;

use YOOtheme\Config;
use YOOtheme\Builder;

return [
    'services' => [
        MigrationService::class => fn (Config $config) => new MigrationService($config->get('yooessentials.version')),
    ],

    'events' => [
        'yooessentials.config.load' => [
            MigrationListener::class => ['migrateConfig', 80],
        ],
    ],

    'extend' => [
        Builder::class => function (Builder $builder, $app) {
            // earliest, but after yoo updateTransform (in case)
            $builder->addTransform('preload', [$app(MigrationService::class), 'migrateNode'], 1);
        },
    ],

    'loaders' => [
        'yooessentials-migrations' => new MigrationLoader(),
    ]
];
