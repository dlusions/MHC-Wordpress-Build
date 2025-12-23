<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

use ZOOlanders\YOOessentials\Source\HasDynamicFields;

trait ExtractsRecordValue
{
    use HasDynamicFields;

    public static function extractRecordValue($field, $record)
    {
        foreach ($record as $key => $value) {
            if (self::encodeField($field) !== self::encodeField($key)) {
                continue;
            }

            return $value;
        }

        return null;
    }
}
