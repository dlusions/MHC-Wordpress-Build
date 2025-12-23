<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration220;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateSocialSharingElement extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.2.12';

    public function migrateNode(object $node, array $params): void
    {
        if ($node->type === 'yooessentials_social_sharing') {
            Arr::updateKeys($node->props, ['gap' => 'grid_gap']);
        }

        if ($node->type === 'yooessentials_social_sharing_mailto') {
            Arr::updateKeys($node->props, ['title' => 'link_title']);
        }

        if ($node->type === 'yooessentials_social_sharing_viber') {
            Arr::updateKeys($node->props, ['title' => 'link_title']);
            Arr::updateKeys($node->props, ['text' => 'shared_text']);
        }
    }
}
