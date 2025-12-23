<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use YOOtheme\Config;
use ZOOlanders\YOOessentials\AbstractDatabaseManager;
use ZOOlanders\YOOessentials\Database\Database;
use function YOOtheme\app;

class DatabaseManager extends AbstractDatabaseManager implements \ZOOlanders\YOOessentials\Database\DatabaseManager
{
    public function createDatabaseFromOptions(array $options): Database
    {
        $config = app(Config::class);
        $defaults = array_merge($config->get('yooessentials.db', []), [
            'user' => defined('DB_USER') ? DB_USER : '',
            'password' => defined('DB_PASSWORD') ? DB_PASSWORD : '',
        ]);

        $options = array_merge($defaults, $options);

        $host = $options['host'] ?? '127.0.0.1';
        $port = $options['port'] ?? null;

        if ($port) {
            $host .= ':' . $port;
        }

        $driver = new WpDb($options['user'], $options['password'], $options['database'], $host);

        if (!$driver->db_connect()) {
            throw new \Exception('Error establishing a database connection.');
        }

        return new \ZOOlanders\YOOessentials\Wordpress\Database($driver);
    }

    public function type(): string
    {
        /** @var \wpdb $wpdb */
        global $wpdb;

        return $wpdb->db_server_info();
    }

    public function serverVersion(): string
    {
        /** @var \wpdb $wpdb */
        global $wpdb;

        return $wpdb->db_version();
    }

    public function collation(): string
    {
        return '';
    }

    public function connectionCollation(): string
    {
        return '';
    }
}
