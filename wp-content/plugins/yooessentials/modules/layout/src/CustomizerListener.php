<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout;

use ZOOlanders\YOOessentials\Config\Config;

class CustomizerListener
{
    public static function initLibraries(Config $config, LayoutManager $manager)
    {
        $libraries = $config->get(LayoutManager::LIBRARIES_CONFIG_KEY, []);

        $manager->setLibraries($libraries);
    }
}
