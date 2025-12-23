<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration140;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// merge rule conditions into same namespace
class MigrateAccessRulesNamespace extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.4.0';

    public function migrateNode(object $node, array $params): void
    {
        foreach ($node->props as $prop => $value) {
            if (!Str::startsWith($prop, 'yooessentials_access_')) {
                continue;
            }

            $type = $prop;
            $condition = (array) $value;

            if (!($condition['state'] ?? false)) {
                continue;
            }

            unset($condition['state']);

            $node->props['yooessentials_access_conditions'][] = (object) [
                'props' => $condition,
                'type' => $type,
            ];

            unset($node->props[$prop]);
        }
    }
}
