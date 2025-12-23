<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Request;

class SourceListener
{
    public static function initSource($source)
    {
        $source->objectType('YooessentialsRequest', Type\RequestType::config());
        $source->objectType('YooessentialsRequestUrl', Type\RequestUrlType::config());
        $source->queryType(Type\RequestQueryType::config());
    }
}
