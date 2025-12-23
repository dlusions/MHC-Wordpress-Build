<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use ZOOlanders\YOOessentials\Database\AbstractDatabase;

class Database extends AbstractDatabase
{
    protected \wpdb $db;

    /**
     * Constructor.
     */
    public function __construct(\wpdb $db)
    {
        $this->db = $db;
        $this->prefix = $db->get_blog_prefix() ?? '';
        $this->driver = $db->is_mysql ? 'mysql' : null;
    }

    public function getWpDb(): \wpdb
    {
        return $this->db;
    }

    /**
     * @inheritdoc
     */
    public function loadResult($statement, array $params = [])
    {
        return $this->db->get_var($this->prepareQuery($statement, $params));
    }

    /**
     * @inheritdoc
     */
    public function fetchAll($statement, array $params = [])
    {
        return $this->db->get_results($this->prepareQuery($statement, $params), ARRAY_A);
    }

    /**
     * @inheritdoc
     */
    public function fetchAssoc($statement, array $params = [])
    {
        return $this->db->get_row($this->prepareQuery($statement, $params), ARRAY_A);
    }

    /**
     * @inheritdoc
     */
    public function fetchArray($statement, array $params = [])
    {
        return $this->db->get_row($this->prepareQuery($statement, $params), ARRAY_N);
    }

    /**
     * @inheritdoc
     */
    public function executeQuery($query, array $params = [])
    {
        return $this->db->query($this->prepareQuery($query, $params));
    }

    /**
     * @inheritdoc
     */
    public function insert($table, $data)
    {
        return $this->db->insert($this->replacePrefix($table), $data);
    }

    /**
     * @inheritdoc
     */
    public function update($table, $data, $identifier)
    {
        return $this->db->update($this->replacePrefix($table), $data, $identifier);
    }

    /**
     * @inheritdoc
     */
    public function delete($table, $identifier)
    {
        return $this->db->delete($this->replacePrefix($table), $identifier);
    }

    /**
     * @inheritdoc
     */
    public function escape($text)
    {
        return "'{$this->db->_escape($text)}'";
    }

    /**
     * @inheritdoc
     */
    public function lastInsertId()
    {
        return $this->db->insert_id;
    }

    /**
     * @inheritdoc
     */
    public function createQuery(): DatabaseQuery
    {
        return new DatabaseQuery($this);
    }
}
