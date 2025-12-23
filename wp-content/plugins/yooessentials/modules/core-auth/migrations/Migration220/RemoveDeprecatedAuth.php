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

class RemoveDeprecatedAuth extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.2.28';

    public function migrateConfig(array $config): array
    {
        $entries = Arr::get($config, 'auth.auths', []);

        if (empty($entries)) {
            return $config;
        }

        $entries = Arr::filter($entries, fn ($auth) => $auth['driver'] !== 'instagrambasic');

        Arr::set($config, 'auth.auths', $entries);

        return $config;
    }
}
