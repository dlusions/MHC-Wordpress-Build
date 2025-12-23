<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration240;

use function YOOtheme\app;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Config\ConfigEncrypter;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class DecryptPublicKeys extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.4.0';

    public function migrateConfig(array $config): array
    {
        $auths = Arr::get($config, 'auth.auths', []);

        if (empty($auths)) {
            return $config;
        }

        $encrypter = app(ConfigEncrypter::class);

        foreach ($auths as &$auth) {
            $driver = $auth['driver'] ?? null;

            if (in_array($driver, ['linkedin', 'twitter', 'tiktok', 'google'], true)) {
                $v = $auth['clientId'] ?? '';
                $auth['clientId'] = $encrypter->decrypt($v) ?: $v;
            }

            if ($driver === 'aws') {
                $v = $auth['access_key_id'] ?? '';
                $auth['access_key_id'] = $encrypter->decrypt($v) ?: $v;
            }

            if ($driver === 'basic-auth') {
                $v = $auth['username'] ?? '';
                $auth['username'] = $encrypter->decrypt($v) ?: $v;
            }
        }

        Arr::set($config, 'auth.auths', $auths);

        return $config;
    }
}
