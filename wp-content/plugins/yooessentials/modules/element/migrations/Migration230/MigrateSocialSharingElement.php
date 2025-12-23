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

class MigrateSocialSharingElement extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.2';

    public function type(): string
    {
        return 'yooessentials_social_sharing';
    }

    public function migrateNode(object $node, array $params): void
    {
        Arr::del($node->props, 'gap');

        if (!empty($node->props['icon_width'])) {
            $node->props['image_width'] = $node->props['icon_width'];
            $node->props['image_height'] = $node->props['icon_width'];
        }

        Arr::updateKeys($node->props, [
            'grid_gap' => fn ($value) => ['grid_column_gap' => $value, 'grid_row_gap' => $value],
        ]);

        // Previous version still had grid_gap as default
        unset($node->props['grid_gap']);

        if (!Arr::has($node->props, 'grid_column_gap')) {
            $node->props['grid_column_gap'] = '';
        }

        if (!Arr::has($node->props, 'grid_row_gap')) {
            $node->props['grid_row_gap'] = '';
        }

        // Remove deprecated prop
        unset($node->props['inline_align']);
    }
}
