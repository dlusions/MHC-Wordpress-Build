<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

interface HasCacheTimes
{
    public const DEFAULT_CACHE_TIME = 3600;
    public const MIN_CACHE_TIME = 3600;

    public function defaultCacheTime(): int;

    public function minCacheTime(): int;
}
