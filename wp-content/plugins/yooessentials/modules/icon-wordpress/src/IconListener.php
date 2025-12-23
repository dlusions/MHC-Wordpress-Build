<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon\Wordpress;

use ZOOlanders\YOOessentials\Icon\IconLoader;

class IconListener
{
    // using a script workaround as in WordPress the head is inited too early
    public static function loadIcons(IconLoader $loader)
    {
        if (($icons = $loader->queued()) and !empty($icons)) {
            echo sprintf('<script data-preview="diff">UIkit.icon.add(%s)</script>', json_encode($icons));
        }
    }
}
