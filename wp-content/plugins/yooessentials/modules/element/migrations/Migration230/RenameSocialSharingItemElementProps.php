<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RenameSocialSharingItemElementProps extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.4';

    public function type(): string
    {
        return 'yooessentials_social_sharing_item';
    }

    public function migrateNode(object $node, array $params): void
    {
        Arr::updateKeys($node->props, ['link' => 'network']);
        Arr::updateKeys($node->props, ['text' => 'shared_text']);
        Arr::updateKeys($node->props, ['title' => 'link_title']);
    }
}
