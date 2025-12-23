<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration120;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateSocialSharingElement extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.2.0';

    public function type(): string
    {
        return 'yooessentials_social_sharing_item';
    }

    public function migrateNode(object $node, array $params): void
    {
        // link_target is a boolean in the old version, convert it to string
        if (is_bool($node->props['link_target'] ?? '')) {
            $node->props['link_target'] = $node->props['link_target'] ? '_blank' : '_self';
        }
    }
}
