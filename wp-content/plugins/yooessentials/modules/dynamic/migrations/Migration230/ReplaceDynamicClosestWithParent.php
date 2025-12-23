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

class ReplaceDynamicClosestWithParent extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.0';

    public function migrateNode(object $node, array $params): void
    {
        Migration230Helper::iterateNodeSources($node, function ($source) {
            if (isset($source->query->name) && $source->query->name === '~closest') {
                $source->query->name = '#parent';
            }
        });

        // remove source_extended props already present in source props
        // (seems to be a bug in some previous version)
        $sourceProps = (array) ($node->source->props ?? []);

        foreach ((array) ($node->source_extended->props ?? []) as $name => $prop) {
            if (isset($sourceProps[$name])) {
                if (is_array($node->source_extended->props)) {
                    unset($node->source_extended->props[$name]);
                } else {
                    unset($node->source_extended->props->{$name});
                }
            }
        }
    }
}
