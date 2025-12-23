<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use ZOOlanders\YOOessentials\Migration\Migration230Helper;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RemoveSourceFiltersMetadata extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.0';

    public function migrateNode(object $node, array $params): void
    {
        Migration230Helper::iterateNodeSources($node, function ($source) {
            // remove outdated meta props in filters and ordering
            foreach (['filters', 'ordering'] as $arg) {
                foreach ((array) ($source->query->arguments->{$arg} ?? []) as &$condition) {
                    if (!isset($condition->props)) {
                        continue;
                    }

                    $metaProperties = array_filter(array_keys(get_object_vars($condition->props)), fn (string $property) => stripos($property, '_') === 0);

                    foreach ($metaProperties as $property) {
                        unset($condition->props->{$property});
                    }
                }
            }
        });
    }
}
