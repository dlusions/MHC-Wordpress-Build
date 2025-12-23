<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml;

use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;
use ZOOlanders\YOOessentials\Dynamic\Resolvers\SourceQueryConditionsResolver;

return [
    'routes' => [
        ['post', XmlController::PRESAVE_ENDPOINT, XmlController::class . '@presave'],
        ['post', XmlController::METADATA_ENDPOINT, XmlController::class . '@metadata']
    ],

    'yooessentials-sources' => [
        'xml-file' => XmlSourceFile::class,
        'xml-url' => XmlSourceUrl::class,
    ],

    'services' => [
        XmlService::class => [
            'class' => XmlService::class,
            'shared' => false,
        ]
    ],

    'extend' => [
        SourceResolverManager::class => function (SourceResolverManager $manager, $app) {
            $manager->addResolver(new SourceQueryConditionsResolver('/xml\w{6}\./'), 1); // early execution
        },
    ]
];
