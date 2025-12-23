<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

use YOOtheme\Str;

abstract class Prop
{
    /**
     * Parse a prop string value
     *
     * @param object|array $props The props holder
     * @param string $key The prop key
     * @param string $default The default value
     * @param string $length The max lenght of the returned string
     */
    public static function parseString($props, string $key, ?string $default = null, ?int $length = null): string
    {
        $props = (array) $props;
        $value = trim($props[$key] ?? $default) ?: $default;

        return Str::substr($value, 0, $length);
    }

    /**
     * Returns filtered array by keys matching a prefix. The prefix is removed.
     *
     * @param string|array $prefixes Prefixed to filter by.
     */
    public static function filterByPrefix(array $data, $prefixes): array
    {
        $result = [];

        foreach ((array) $prefixes as $prefix) {
            $name = str_replace('_', '', $prefix);

            $result[$name] = array_reduce(
                array_keys($data),
                function ($carry, $key) use ($data, $prefix) {
                    if (Str::startsWith($key, $prefix)) {
                        $carry[str_replace($prefix, '', $key)] = $data[$key];
                    }

                    return $carry;
                },
                []
            );
        }

        return is_array($prefixes) ? $result : array_pop($result);
    }
}
