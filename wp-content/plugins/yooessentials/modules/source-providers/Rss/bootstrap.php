<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss;

use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;
use ZOOlanders\YOOessentials\Dynamic\Resolvers\SourceQueryConditionsResolver;

return [
    'routes' => [['post', RssController::PRESAVE_ENDPOINT, RssController::class . '@presave']],

    'yooessentials-sources' => [
        'rss' => RssSource::class,
    ],

    'services' => [
        RssService::class => '',
    ],

    'extend' => [
        SourceResolverManager::class => function (SourceResolverManager $manager, $app) {
            $manager->addResolver(new SourceQueryConditionsResolver('/rss\w{6}\./'), 1); // early execution
        },
    ]
];
