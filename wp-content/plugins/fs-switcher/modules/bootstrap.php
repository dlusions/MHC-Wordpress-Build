<?php /**
 * @package     [FS] Switcher Pro for YOOtheme Pro
 * @subpackage  fs-switcher
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/switcher
 * @build       (FLART_BUILD_NUMBER)
 */

namespace FlartStudio\YOOtheme\Switcher;

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Config;
use YOOtheme\Path;
use YOOtheme\Builder;
use YOOtheme\Theme\Styler\StylerConfig;

include_once Path::get('./src/StyleListener.php', __DIR__);
include_once Path::get('./src/FormListener.php', __DIR__);
include_once Path::get('./src/TranslationListener.php', __DIR__);

return [
    'theme' => [
        'styles' => [
            'components' => [
                'fs_switcher_pro' => Path::get('./assets/less/fs-switcher.less', __DIR__),
            ],
        ],
    ],
    'events' => [
        StylerConfig::class => [StyleListener::class => '@handle'],
        'builder.type' => [FormListener::class => ['extend', 10]],
        'customizer.init' => [TranslationListener::class => ['translate', 10]],
    ],
    'extend' => [
        Config::class => static function (Config $config) {
            $config->addFile('fs_switcher', Path::get('./config/fs-switcher.json', __DIR__));
            $config->addFilter('fs_switcher', function ($value) use ($config) {
                return $config->get("fs_switcher.$value");
            });
        },
        Builder::class => static function (Builder $builder) {
            $builder->addTypePath(Path::get('./element/switcher*/*/element.json', __DIR__));
        }
    ],
];