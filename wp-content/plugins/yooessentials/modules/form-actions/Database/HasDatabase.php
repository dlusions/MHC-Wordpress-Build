<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Database;

use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Database\DatabaseManager;
use ZOOlanders\YOOessentials\Util;
use function YOOtheme\app;

trait HasDatabase
{
    protected ?Database $db = null;

    public function db(array $config): Database
    {
        if ($this->db) {
            return $this->db;
        }

        $options = Util\Prop::filterByPrefix($config, 'db_');
        $options = array_filter($options);

        $options['external'] = (bool) ($config['external'] ?? false);

        $options['database'] = $options['name'] ?? '';
        unset($options['name']);

        return $this->db = app(DatabaseManager::class)->initialize($options);
    }
}
