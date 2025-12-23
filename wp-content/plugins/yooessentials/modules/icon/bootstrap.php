<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon;

use function YOOtheme\app;
use YOOtheme\Builder;

return [
    'events' => [
        'customizer.init' => [
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'theme.init' => [
            Listener\LoadIcons::class => ['@handle', -20],
        ],
    ],

    'routes' => [
        ['post', '/yooessentials/icon/list', IconController::class . '@getIcons'],
        ['post', '/yooessentials/icon/collection/add', IconController::class . '@addCollection'],
        ['post', '/yooessentials/icon/collection/remove', IconController::class . '@removeCollection'],
    ],

    'extend' => [
        Builder::class => function (Builder $builder, $app) {
            $builder->addTransform('prerender', app(IconTransform::class));
        },
    ],

    'services' => [
        IconApi::class => '',
        IconLoader::class => ''
    ],
];
