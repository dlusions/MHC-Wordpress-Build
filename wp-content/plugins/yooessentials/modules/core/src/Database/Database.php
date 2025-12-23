<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Database;

interface Database
{
    /**
     * Loads the first field of the first row of the result.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return mixed|null
     */
    public function loadResult($statement, array $params = []);

    /**
     * Fetches all rows of the result as an associative array.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return array
     */
    public function fetchAll($statement, array $params = []);

    /**
     * Fetches the first row of the result as an associative array.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return array
     */
    public function fetchAssoc($statement, array $params = []);

    /**
     * Fetches the first row of the result as a numerically indexed array.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return array
     */
    public function fetchArray($statement, array $params = []);

    /**
     * Prepares and executes an SQL query and returns the first row of the result as an object.
     *
     * @param string $statement
     * @param array  $params
     * @param string $class
     * @param array  $args
     *
     * @return object|null
     */
    public function fetchObject($statement, array $params = [], $class = 'stdClass', $args = []);

    /**
     * Prepares and executes an SQL query and returns the result as an array of objects.
     *
     * @param string $statement
     * @param array  $params
     * @param string $class
     * @param array  $args
     *
     * @return array
     */
    public function fetchAllObjects($statement, array $params = [], $class = 'stdClass', $args = []);

    /**
     * Executes an SQL query, optionally parametrized, SQL query.
     *
     * @param string $query
     * @param array  $params
     *
     * @return int|false
     */
    public function executeQuery($query, array $params = []);

    /**
     * Inserts a table row with specified data.
     *
     * @param string $table
     * @param mixed  $data
     *
     * @return int|bool
     */
    public function insert($table, $data);

    /**
     * Updates a table row with specified data.
     *
     * @param string $table
     * @param mixed  $data
     * @param array  $identifier
     *
     * @return int|bool
     */
    public function update($table, $data, $identifier);

    /**
     * Deletes a table row.
     *
     * @param string $table
     * @param array  $identifier
     *
     * @return int|bool
     */
    public function delete($table, $identifier);

    /**
     * Escapes a string for usage in an SQL statement.
     *
     * @param string $text
     *
     * @return string
     */
    public function escape($text);

    /**
     * Retrieves the last inserted id.
     *
     * @return int
     */
    public function lastInsertId();

    /**
     * Add quotes to the text if necessary
     * Recursive for arrays, and does not quote numbers, but instead casts them to the right type
     *
     * @param array|float|int|string $text
     * @return array|float|int|string
     */
    public function quote($text);

    /**
     * Adds quotes both the table name and the field name, skipping the `.` in between
     * Example: quotes.total => `quotes`.`total`
     *
     * @param array $strArr
     * @return string
     */
    public function quoteNameStr(array $strArr): string;

    /**
     * Creates a new Database Query object
     *
     * @return DatabaseQuery
     */
    public function createQuery(): DatabaseQuery;
}
