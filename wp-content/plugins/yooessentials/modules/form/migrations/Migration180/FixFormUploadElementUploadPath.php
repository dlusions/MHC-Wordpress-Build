<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration180;

use YOOtheme\Str;
use YOOtheme\File;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class FixFormUploadElementUploadPath extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.8.13';

    public function type(): string
    {
        return 'yooessentials_form_upload';
    }

    public function migrateNode(object $node, array $params): void
    {
        $uploadPath = $node->props['control_upload_path'] ?? null;

        if ($uploadPath && Str::startsWith($uploadPath, '/') && !File::exists($uploadPath)) {
            $node->props['control_upload_path'] = ltrim($uploadPath, '\/\\');
        }
    }
}
