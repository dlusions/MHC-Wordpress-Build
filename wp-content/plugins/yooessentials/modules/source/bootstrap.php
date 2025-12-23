<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source;

use ZOOlanders\YOOessentials\Migration;

return [
    'routes' => [
        ['post', SourceController::REBUILD_SCHEMA_URL, SourceController::class . '@rebuildSchema'],
    ],

    'events' => [
        'source.init' => [
            Listener\RegisterSources::class => ['@handle', 60],
        ],

        'customizer.init' => [
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'yooessentials.config.load' => [
            Listener\ValidateSources::class => ['@load'],
        ],
    ],

    'loaders' => [
        'yooessentials-sources' => new SourceLoader(),
    ],

    'services' => [
        SourceService::class => '',
    ],

    'yooessentials-migrations' => [
        Migration\Migration160\MigrateSources::class,
        Migration\Migration160\CleanupCorruptedSources::class,
        Migration\Migration180\MigrateSourceProviders::class,
        Migration\Migration210\MigrateDatabaseSourceConnections::class,
        Migration\Migration220\RemoveDuplicatedSources::class,
        Migration\Migration220\RenameInstagramSource::class,
        Migration\Migration230\RemoveSourceFiltersMetadata::class,
        Migration\Migration230\RenameSourceProviders::class,
        Migration\Migration230\RenameSourceQueries::class,
    ],
];
