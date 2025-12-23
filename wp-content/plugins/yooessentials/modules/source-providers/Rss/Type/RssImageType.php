<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use ZOOlanders\YOOessentials\Source\Provider\Xml\Type\XmlImageType;
use ZOOlanders\YOOessentials\Util;

class RssImageType extends XmlImageType
{
    public const NAME = 'RSSImage';
    public const LABEL = 'Image';

    public static function url($image): string
    {
        $url = $image['url'] ?? '';
        $cacheKey = 'rss-image-' . sha1($url);

        return Util\File::cacheMedia($url, $cacheKey);
    }
}
