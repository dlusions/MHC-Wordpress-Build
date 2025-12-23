<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon\Wordpress;

use YOOtheme\Path;
use ZOOlanders\YOOessentials\Icon\IconLoader;

return [
    'actions' => [
        'wp_print_footer_scripts' => [
            IconListener::class => 'loadIcons',
        ],
    ],

    'extend' => [
        IconLoader::class => function (IconLoader $loader, $app) {
            $loader->setLocation(
                Path::join($app->config->get('app.contentDir'), '/yooessentials/icons')
            );
        },
    ],
];
