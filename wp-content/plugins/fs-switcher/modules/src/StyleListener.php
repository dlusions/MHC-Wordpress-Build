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

use YOOtheme\Config as ThemeConfig;
use YOOtheme\File;
use YOOtheme\Theme\Styler\StylerConfig;

class StyleListener
{
    public static function handle(StylerConfig $config, ThemeConfig $settings): StylerConfig
    {
        $file = File::find("~theme/css/theme{.{$settings->get('theme.id')},}.css");
        if ($file !== null && File::exists($file)) {
            $isCompiled = strpos(File::getContents($file), '.fs-switcher__modal');
            if ($isCompiled === false) {
                $config['update'] = true;
            }
        }
        return $config;
    }
}