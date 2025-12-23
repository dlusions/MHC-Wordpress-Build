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
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RemoveDuplicatedStorages extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.2.12';

    public function migrateConfig(array $config): array
    {
        $entries = Arr::get($config, 'storages', []);

        if (empty($entries)) {
            return $config;
        }

        $entries = Util\Arr::removeDuplicates($entries);

        Arr::set($config, 'storages', $entries);

        return $config;
    }
}
