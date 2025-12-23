<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateSocialSharingItemElement extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.10';

    public function type(): string
    {
        return 'yooessentials_social_sharing_item';
    }

    public function migrateNode(object $node, array $params): void
    {
        $network = $node->props['network'] ?? '';

        if ($network === 'twitter') {
            $node->props['network'] = 'x';
        }

        if ($network === 'custom' && isset($node->props['custom_link'])) {
            $node->props['custom_link'] = sprintf($node->props['custom_link'], '{URL}', '{TEXT}');
        }
    }
}
