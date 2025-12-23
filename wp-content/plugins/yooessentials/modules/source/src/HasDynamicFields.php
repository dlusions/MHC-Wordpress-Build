<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source;

use YOOtheme\Str;

trait HasDynamicFields
{
    public static function encodeField(string $field): string
    {
        // replaces unicode and dashes with lower dash
        $field = preg_replace('/%.{2}|-/', '_', rawurlencode($field));

        // move edge dashes
        $field = preg_replace('/^_|_#/', '', $field);

        // dots => _
        $field = preg_replace('/\./', '_', $field);

        // lowercase
        $field = Str::lower($field);

        // enforce string
        if (is_numeric($field)) {
            $field = "_{$field}";
        }

        // no empty values allowed
        if (empty($field)) {
            $field = '_';
        }

        // enforce starting with string
        if (is_numeric(substr($field, 0, 1))) {
            $field = "_$field";
        }

        return $field;
    }

    /**
     * Formats label from a field key
     *
     * @example
     * self::labelField('assets_id');
     * // => Assets ID
     */
    public static function labelField(string $field): string
    {
        $field = Str::snakeCase($field, ' ');
        $field = Str::titleCase($field);
        $field = str_replace('_', ' ', $field);
        $field = preg_replace('/ id$/i', ' ID', $field);
        $field = preg_replace('/^id /i', 'ID ', $field);
        $field = preg_replace('/^id$/i', 'ID', $field);

        return $field;
    }
}
