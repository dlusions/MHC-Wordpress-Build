<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration170;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// convert source to source_extended custom prop query
class MigrateDynamicQueries extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.7.0';

    public function migrateNode(object $node, array $params): void
    {
        foreach ($node->props['yooessentials_access_conditions'] ?? [] as $condition) {
            if (!isset($condition->source->query)) {
                continue;
            }

            $condition->source_extended = (object) ['props' => (object) []];

            foreach ($condition->source->props ?? [] as $name => $prop) {
                $prop->query = $condition->source->query;
                $condition->source_extended->props->{$name} = $prop;
            }

            unset($condition->source);
        }
    }
}
