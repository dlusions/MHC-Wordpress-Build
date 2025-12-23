<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

interface UrlInterface
{
    public const TLS_IGNORE = 0;
    public const TLS_FORCE = 1;
    public const TLS_DISABLE = 2;

    public function cmsRoute(string $url, bool $xhtml = true, int $tls = UrlInterface::TLS_IGNORE, bool $absolute = false): string;
}
