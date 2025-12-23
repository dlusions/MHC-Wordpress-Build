<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

use ZOOlanders\YOOessentials\Dynamic\Resolvers\GlobalSourceQueryResolver;

abstract class Config
{
    public static function getGlobalQueries(array &$config): array
    {
        return Arr::get($config, GlobalSourceQueryResolver::GLOBAL_QUERIES_CONFIG_KEY, []);
    }

    public static function setGlobalQueries(array &$config, array $queries)
    {
        Arr::set($config, GlobalSourceQueryResolver::GLOBAL_QUERIES_CONFIG_KEY, $queries);
    }

    public static function filter(array &$config, string $key, callable $cb)
    {
        $sources = Arr::get($config, $key, []);
        $sources = array_values(array_filter($sources, $cb));

        Arr::set($config, $key, $sources);
    }

    public static function iterate(array &$config, string $key, callable $cb)
    {
        $sources = Arr::get($config, $key, []);

        foreach ($sources as &$source) {
            $cb($source, $sources);
        }

        Arr::set($config, $key, $sources);
    }

    public static function iterateGlobalQueries(array &$config, callable $cb)
    {
        $queries = self::getGlobalQueries($config);

        foreach ($queries as &$global) {
            if (isset($global['source']['query'])) {
                $query = (object) $global['source']['query'];

                $cb($query);

                $global['source']['query'] = (array) $query;
            }
        }

        self::setGlobalQueries($config, $queries);
    }
}
