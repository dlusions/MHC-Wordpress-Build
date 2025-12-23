<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use ZOOlanders\YOOessentials\UrlInterface;

class Url extends \YOOtheme\Url implements UrlInterface
{
    public function cmsRoute(string $url, bool $xhtml = true, int $tls = UrlInterface::TLS_IGNORE, bool $absolute = false): string
    {
        // Relative url in wp are prefixed with ./ since link picker prints out urls as relative...
        if (stripos($url, './') === 0) {
            return substr($url, 2);
        }

        if (stripos($url, '/') === 0) {
            return $url;
        }

        // External urls
        if (stripos($url, 'http') === 0) {
            return $url;
        }

        // Same as before. Treat relative urls as absolute.
        return '/' . $url;
    }
}
