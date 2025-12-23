<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database;

use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;
use ZOOlanders\YOOessentials\Dynamic\Resolvers\SourceQueryConditionsResolver;

return [
    'routes' => [
        ['post', DatabaseController::TABLES_URL, DatabaseController::class . '@tables'],
        ['post', DatabaseController::FIELDS_URL, DatabaseController::class . '@fields'],
        ['post', DatabaseController::RECORDS_URL, DatabaseController::class . '@records'],
        ['post', DatabaseController::FILTER_FIELDS_URL, DatabaseController::class . '@filterFields'],
        ['post', DatabaseController::PRESAVE_ENDPOINT, DatabaseController::class . '@presave'],
    ],

    'yooessentials-sources' => [
        'database' => DatabaseSource::class,
    ],

    'extend' => [
        SourceResolverManager::class => function (SourceResolverManager $manager, $app) {
            $manager->addResolver(new SourceQueryConditionsResolver('/database\w{6}\./'), 1); // early execution
        },
    ],
];
