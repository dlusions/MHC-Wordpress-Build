<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml\Parsers;

use ZOOlanders\YOOessentials\Source\Provider\Xml\Xml;

class ArrayParser implements XmlParser
{
    public function shouldParse(string $tag, $value): bool
    {
        return is_array($value);
    }

    public function parse(string $tag, $value)
    {
        return (new Xml())->parseData($value);
    }
}
