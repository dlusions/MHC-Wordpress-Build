<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration110;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateSocialSharingElement extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.1.0';

    public function type(): string
    {
        return 'yooessentials_social_sharing';
    }

    public function migrateNode(object $node, array $params): void
    {
        if (!empty($node->props['icon_ratio'])) {
            $node->props['icon_width'] = round(20 * $node->props['icon_ratio']);
            unset($node->props['icon_ratio']);
        }
    }
}
