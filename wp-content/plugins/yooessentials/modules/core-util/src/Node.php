<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

abstract class Node
{
    public static function findClosest(array $params, callable $match): ?object
    {
        foreach ($params['path'] ?? [] as $ancestor) {
            if ($match($ancestor)) {
                return $ancestor;
            }
        }

        return null;
    }

    public static function findClosestType(array $params, string $type): ?object
    {
        return self::findClosest($params, fn ($node) => $node->type === $type);
    }
}
