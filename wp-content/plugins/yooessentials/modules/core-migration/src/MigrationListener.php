<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration;

class MigrationListener
{
    public static function migrateConfig(MigrationService $migrations, $values)
    {
        return $migrations->migrateConfig($values);
    }
}
