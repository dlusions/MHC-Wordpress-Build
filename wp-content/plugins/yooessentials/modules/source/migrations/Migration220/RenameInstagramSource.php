<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration220;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RenameInstagramSource extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.2.28';

    public function migrateConfig(array $config): array
    {
        Util\Config::iterate($config, 'source.sources', function (&$source) {
            $provider = $source['provider'] ?? '';

            if ($provider === 'instagram_business') {
                $source['provider'] = 'instagram';
            }
        });

        return $config;
    }
}
