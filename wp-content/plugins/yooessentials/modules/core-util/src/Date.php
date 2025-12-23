<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

abstract class Date
{
    public static function from(string $date): ?\DateTime
    {
        if (!$date) {
            return null;
        }

        try {
            return new \DateTime($date);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function humanize(int $duration): string
    {
        $parsed = [
            's' => $duration % 60,
            'm' => floor(($duration % 3600) / 60),
            'h' => floor(($duration % 86400) / 3600),
            'd' => floor(($duration % 2592000) / 86400),
            'M' => floor($duration / 2592000),
        ];

        $result = array_filter($parsed);

        $result = array_map(
            fn ($key, $v) => "$v$key",
            array_keys($result),
            $result
        );

        return implode(' ', array_reverse($result));
    }
}
