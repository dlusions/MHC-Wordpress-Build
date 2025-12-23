<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration200;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateDynamicKeys extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.0.0';

    public function migrateNode(object $node, array $params): void
    {
        if (!($node->source_extended ?? false)) {
            return;
        }

        $globalKey = '~global';
        $closestKey = '~closest';

        // query global
        if ($node->source_extended->query->global ?? false) {
            $node->source ??= (object) [];
            $node->source->query ??= (object) [];
            $node->source->query->name = $globalKey . ':' . $node->source_extended->query->global;

            unset($node->source_extended->query);
        }

        // props global
        foreach ((array) ($node->source_extended->props ?? []) as $key => $prop) {
            if (!($prop->query->global ?? false)) {
                continue;
            }

            $node->source ??= (object) [];
            $node->source->props = (object) ($node->source->props ?? []);
            $node->source->props->$key = clone $prop;
            $node->source->props->$key->query = (object) [
                'name' => $globalKey . ':' . $prop->query->global,
            ];

            unset($node->source_extended->props->$key);
        }

        // props closest
        foreach ((array) ($node->source_extended->props ?? []) as $key => $prop) {
            if (!($prop->query->inherit ?? false)) {
                continue;
            }

            $node->source ??= (object) [];
            $node->source->props = (object) ($node->source->props ?? []);
            $node->source->props->$key = clone $prop;
            $node->source->props->$key->query = (object) [
                'name' => $closestKey,
            ];

            unset($node->source_extended->props->$key);
        }

        // cleanup
        $node->source_extended->props = array_filter((array) ($node->source_extended->props ?? []));

        if (empty($node->source_extended->props)) {
            unset($node->source_extended->props);
        }

        if (empty((array) $node->source_extended)) {
            unset($node->source_extended);
        }
    }
}
