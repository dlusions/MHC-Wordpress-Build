<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration220;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class FixFormFieldsetRegression extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.2.0';

    public function type(): string
    {
        return 'yooessentials_form_fieldset';
    }

    public function migrateNode(object $node, array $params): void
    {
        $firstChild = $node->children[0] ?? null;

        if ($firstChild && $firstChild->type === 'row') {
            $column = $firstChild->children[0] ?? null;

            $node->children = $column ? $column->children : $node->children;
        }
    }
}
