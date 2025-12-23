<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration210;

use function YOOtheme\app;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Config\ConfigEncrypter;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// move database connections from the source config to auths
class MigrateDatabaseSourceConnections extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.1.0';

    public function migrateConfig(array $config): array
    {
        $sources = Arr::get($config, 'source.sources', []);

        if (empty($sources)) {
            return $config;
        }

        /** @var ConfigEncrypter */
        $encrypter = app(ConfigEncrypter::class);

        $newauths = [];

        array_walk($sources, function (array &$source) use (&$newauths, $encrypter) {
            if (($source['provider'] ?? '') !== 'database' && ($source['external'] ?? false)) {
                return;
            }

            $keysToMove = ['db_user', 'db_password', 'db_port', 'db_host'];

            $auth = array_fill_keys($keysToMove, null);
            $auth = array_merge($auth, Arr::pick($source, $keysToMove));

            if (empty(array_filter($auth))) {
                return;
            }

            foreach ($keysToMove as $key) {
                unset($source[$key]);
            }

            $id = sha1(json_encode($auth) ?: uniqid());

            $auth['id'] = $id;
            $auth['driver'] = 'database';
            $auth['db_password'] = $encrypter->encrypt($auth['db_password']);

            $newauths[$id] = $auth;

            $source['db_connection'] = $id;
        });

        if (!empty($newauths)) {
            $auths = Arr::get($config, 'auth.auths', []);
            $auths = array_merge($auths, array_values($newauths));

            Arr::set($config, 'auth.auths', $auths);
        }

        Arr::set($config, 'source.sources', $sources);

        return $config;
    }
}
