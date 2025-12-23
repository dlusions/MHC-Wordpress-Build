<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

abstract class Arr extends \YOOtheme\Arr
{
    /**
     * Run an associative map over each of the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @template TKey
     * @template TValue
     * @template TMapWithKeysKey of array-key
     * @template TMapWithKeysValue
     *
     * @param  array<TKey, TValue>  $array
     * @param  callable(TValue, TKey): array<TMapWithKeysKey, TMapWithKeysValue>  $callback
     * @return array
     */
    public static function mapWithKeys(array $array, callable $callback): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $assoc = $callback($value, $key);

            foreach ($assoc as $mapKey => $mapValue) {
                $result[$mapKey] = $mapValue;
            }
        }

        return $result;
    }

    public static function map(array $array, $callback): array
    {
        return array_map($callback, $array);
    }

    public static function explodeList(string $value): array
    {
        return array_filter(explode(',', str_replace([' ', "\r", "\n"], ['', '', ','], $value)));
    }

    /**
     * Index an array by a key
     *
     * @param array|\ArrayAccess $array
     * @param string|callable $key
     *
     * @return array
     */
    public static function keyBy(array $array, $keyBy): array
    {
        $results = [];

        foreach ($array as $item) {
            $resolvedKey = self::resolveKey($item, $keyBy);

            if (is_null($resolvedKey)) {
                continue;
            }

            if (is_object($resolvedKey)) {
                $resolvedKey = (string) $resolvedKey;
            }

            $results[$resolvedKey] = $item;
        }

        return $results;
    }

    private static function resolveKey($item, $key)
    {
        if (is_callable($key)) {
            return $key($item);
        }

        if (is_array($item)) {
            return $item[$key] ?? null;
        }

        if (is_object($item)) {
            return $item->{$key} ?? null;
        }

        return null;
    }

    public static function trim(array $arr): array
    {
        return array_filter(array_map('trim', array_filter($arr)));
    }

    public static function hasStringKeys(array $arr): bool
    {
        return count(array_filter(array_keys($arr), 'is_string')) > 0;
    }

    public static function removeDuplicates(array $entries, string $key = 'id'): array
    {
        $unique = [];

        foreach ($entries as $entry) {
            $unique[$entry[$key]] = $entry;
        }

        return array_values($unique);
    }

    public static function array_find_key(array $array, callable $callback)
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $key;
            }
        }

        return null;
    }
}
