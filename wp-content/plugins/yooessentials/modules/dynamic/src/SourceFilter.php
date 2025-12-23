<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic;

class SourceFilter
{
    public static function applyTime($value, $format)
    {
        if (!$value) {
            return $value;
        }

        if (is_string($value) && !is_numeric($value)) {
            $value = strtotime($value);
        }

        return date($format ?: 'H:i', intval($value) ?: time());
    }

    public static function applyDatemodify($value, $modifier)
    {
        if (!$value || !strtotime($modifier)) {
            return $value;
        }

        if (is_string($value) && !is_numeric($value)) {
            $value = strtotime($value);
        }

        $date = new \DateTime();
        $date->setTimestamp($value);
        $date->modify($modifier);

        return $date->getTimestamp();
    }
}
