<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Database\Database as DatabaseInterface;
use ZOOlanders\YOOessentials\Database\DatabaseQuery as DatabaseQueryInterface;

/** Cloned from Joomla's query class */
class DatabaseQuery implements DatabaseQueryInterface
{
    protected DatabaseInterface $db;
    protected $sql = null;
    protected $type = '';
    protected $element = null;
    protected $select = null;
    protected $delete = null;
    protected $update = null;
    protected $insert = null;
    protected $from = null;
    protected $join = null;
    protected $set = null;
    protected $where = null;
    protected $group = null;
    protected $having = null;
    protected $columns = null;
    protected $values = null;
    protected $order = null;
    protected $autoIncrementField = null;
    protected $call = null;
    protected $exec = null;
    protected $union = null;
    protected $unionAll = null;
    protected $selectRowNumber = null;
    protected $offset = 0;
    protected $limit = 20;

    /**
     * @param Database $db
     */
    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function createForDatabase(Database $database): DatabaseQueryInterface
    {
        return new DatabaseQuery($database);
    }

    public function __call($method, $args)
    {
        if (empty($args)) {
            return;
        }

        switch ($method) {
            case 'q':
                return $this->quote($args[0], isset($args[1]) ? $args[1] : true);

                break;

            case 'qn':
                return $this->quoteName($args[0], isset($args[1]) ? $args[1] : null);

                break;

            case 'e':
                return $this->escape($args[0], isset($args[1]) ? $args[1] : false);

                break;
        }
    }

    /**
     * Magic function to convert the query to a string.
     *
     * @return  string    The completed query.
     *
     * @since   1.7.0
     */
    public function __toString()
    {
        $query = '';

        if ($this->sql) {
            return $this->sql;
        }

        switch ($this->type) {
            case 'element':
                $query .= (string) $this->element;

                break;

            case 'select':
                $query .= (string) $this->select;
                $query .= (string) $this->from;

                if ($this->join) {
                    // Special case for joins
                    foreach ($this->join as $join) {
                        $query .= (string) $join;
                    }
                }

                if ($this->where) {
                    $query .= (string) $this->where;
                }

                if ($this->selectRowNumber === null) {
                    if ($this->group) {
                        $query .= (string) $this->group;
                    }

                    if ($this->having) {
                        $query .= (string) $this->having;
                    }

                    if ($this->union) {
                        $query .= (string) $this->union;
                    }

                    if ($this->unionAll) {
                        $query .= (string) $this->unionAll;
                    }
                }

                if ($this->order) {
                    $query .= (string) $this->order;
                }

                break;

            case 'delete':
                $query .= (string) $this->delete;
                $query .= (string) $this->from;

                if ($this->join) {
                    // Special case for joins
                    foreach ($this->join as $join) {
                        $query .= (string) $join;
                    }
                }

                if ($this->where) {
                    $query .= (string) $this->where;
                }

                if ($this->order) {
                    $query .= (string) $this->order;
                }

                break;

            case 'update':
                $query .= (string) $this->update;

                if ($this->join) {
                    // Special case for joins
                    foreach ($this->join as $join) {
                        $query .= (string) $join;
                    }
                }

                $query .= (string) $this->set;

                if ($this->where) {
                    $query .= (string) $this->where;
                }

                if ($this->order) {
                    $query .= (string) $this->order;
                }

                break;

            case 'insert':
                $query .= (string) $this->insert;

                // Set method
                if ($this->set) {
                    $query .= (string) $this->set;
                }
                // Columns-Values method
                elseif ($this->values) {
                    if ($this->columns) {
                        $query .= (string) $this->columns;
                    }

                    $elements = $this->values->getElements();

                    if (!($elements[0] instanceof $this)) {
                        $query .= ' VALUES ';
                    }

                    $query .= (string) $this->values;
                }

                break;

            case 'call':
                $query .= (string) $this->call;

                break;

            case 'exec':
                $query .= (string) $this->exec;

                break;
        }

        $query .= " LIMIT {$this->offset}, {$this->limit}";

        return $query;
    }

    public function limit($limit): DatabaseQueryInterface
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset($offset): DatabaseQueryInterface
    {
        $this->offset = $offset;

        return $this;
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    public function call($columns)
    {
        $this->type = 'call';

        if (is_null($this->call)) {
            $this->call = new DatabaseQueryElement('CALL', $columns);
        } else {
            $this->call->append($columns);
        }

        return $this;
    }

    public function castAsChar($value)
    {
        return $value;
    }

    public function charLength($field, $operator = null, $condition = null)
    {
        return 'CHAR_LENGTH(' .
            $field .
            ')' .
            (isset($operator) && isset($condition) ? ' ' . $operator . ' ' . $condition : '');
    }

    public function clear($clause = null)
    {
        $this->sql = null;

        switch ($clause) {
            case 'select':
                $this->select = null;
                $this->type = null;
                $this->selectRowNumber = null;

                break;

            case 'delete':
                $this->delete = null;
                $this->type = null;

                break;

            case 'update':
                $this->update = null;
                $this->type = null;

                break;

            case 'insert':
                $this->insert = null;
                $this->type = null;
                $this->autoIncrementField = null;

                break;

            case 'from':
                $this->from = null;

                break;

            case 'join':
                $this->join = null;

                break;

            case 'set':
                $this->set = null;

                break;

            case 'where':
                $this->where = null;

                break;

            case 'group':
                $this->group = null;

                break;

            case 'having':
                $this->having = null;

                break;

            case 'order':
                $this->order = null;

                break;

            case 'columns':
                $this->columns = null;

                break;

            case 'values':
                $this->values = null;

                break;

            case 'exec':
                $this->exec = null;
                $this->type = null;

                break;

            case 'call':
                $this->call = null;
                $this->type = null;

                break;

            case 'limit':
                $this->offset = 0;
                $this->limit = 0;

                break;

            case 'offset':
                $this->offset = 0;

                break;

            case 'union':
                $this->union = null;

                break;

            case 'unionAll':
                $this->unionAll = null;

                break;

            default:
                $this->type = null;
                $this->select = null;
                $this->selectRowNumber = null;
                $this->delete = null;
                $this->update = null;
                $this->insert = null;
                $this->from = null;
                $this->join = null;
                $this->set = null;
                $this->where = null;
                $this->group = null;
                $this->having = null;
                $this->order = null;
                $this->columns = null;
                $this->values = null;
                $this->autoIncrementField = null;
                $this->exec = null;
                $this->call = null;
                $this->union = null;
                $this->unionAll = null;
                $this->offset = 0;
                $this->limit = 0;

                break;
        }

        return $this;
    }

    public function columns($columns)
    {
        if (is_null($this->columns)) {
            $this->columns = new DatabaseQueryElement('()', $columns);
        } else {
            $this->columns->append($columns);
        }

        return $this;
    }

    public function concatenate($values, $separator = null)
    {
        if ($separator) {
            return 'CONCATENATE(' . implode(' || ' . $this->quote($separator) . ' || ', $values) . ')';
        } else {
            return 'CONCATENATE(' . implode(' || ', $values) . ')';
        }
    }

    public function currentTimestamp()
    {
        return 'CURRENT_TIMESTAMP()';
    }

    public function dateFormat()
    {
        return 'Y-m-d H:i:s';
    }

    public function dump()
    {
        return '<pre>' . $this . '</pre>';
    }

    public function delete($table = null)
    {
        $this->type = 'delete';
        $this->delete = new DatabaseQueryElement('DELETE', null);

        if (!empty($table)) {
            $this->from($table);
        }

        return $this;
    }

    public function escape($text, $extra = false)
    {
        return $this->db->getWpDb()->prepare($text, $extra);
    }

    public function exec($columns)
    {
        $this->type = 'exec';

        if (is_null($this->exec)) {
            $this->exec = new DatabaseQueryElement('EXEC', $columns);
        } else {
            $this->exec->append($columns);
        }

        return $this;
    }

    public function from($tables, ?string $subQueryAlias = null): DatabaseQueryInterface
    {
        if (is_null($this->from)) {
            if ($tables instanceof $this) {
                if (is_null($subQueryAlias)) {
                    throw new \RuntimeException('NULL SUBQUERY ALIAS');
                }

                $tables = '( ' . (string) $tables . ' ) AS ' . $this->quoteName($subQueryAlias);
            }

            $this->from = new DatabaseQueryElement('FROM', $tables);
        } else {
            $this->from->append($tables);
        }

        return $this;
    }

    public function year($date)
    {
        return 'YEAR(' . $date . ')';
    }

    public function month($date)
    {
        return 'MONTH(' . $date . ')';
    }

    public function day($date)
    {
        return 'DAY(' . $date . ')';
    }

    public function hour($date)
    {
        return 'HOUR(' . $date . ')';
    }

    public function minute($date)
    {
        return 'MINUTE(' . $date . ')';
    }

    public function second($date)
    {
        return 'SECOND(' . $date . ')';
    }

    public function group($columns)
    {
        if (is_null($this->group)) {
            $this->group = new DatabaseQueryElement('GROUP BY', $columns);
        } else {
            $this->group->append($columns);
        }

        return $this;
    }

    public function having($conditions, $glue = 'AND')
    {
        if (is_null($this->having)) {
            $glue = strtoupper($glue);
            $this->having = new DatabaseQueryElement('HAVING', $conditions, " $glue ");
        } else {
            $this->having->append($conditions);
        }

        return $this;
    }

    public function innerJoin($condition)
    {
        $this->join('INNER', $condition);

        return $this;
    }

    public function insert($table, $incrementField = false)
    {
        $this->type = 'insert';
        $this->insert = new DatabaseQueryElement('INSERT INTO', $table);
        $this->autoIncrementField = $incrementField;

        return $this;
    }

    public function join($type, $conditions)
    {
        if (is_null($this->join)) {
            $this->join = [];
        }

        $this->join[] = new DatabaseQueryElement(strtoupper($type) . ' JOIN', $conditions);

        return $this;
    }

    public function leftJoin(
        $table,
        $firstColumn,
        $operator = '=',
        $secondColumn = null
    ): DatabaseQueryInterface {
        $this->join('LEFT', "{$table} ON {$firstColumn} {$operator} {$secondColumn}");

        return $this;
    }

    public function length($value)
    {
        return 'LENGTH(' . $value . ')';
    }

    public function nullDate($quoted = true)
    {
        return null;
    }

    public function order($columns)
    {
        if (is_null($this->order)) {
            $this->order = new DatabaseQueryElement('ORDER BY', $columns);
        } else {
            $this->order->append($columns);
        }

        return $this;
    }

    public function outerJoin($condition)
    {
        $this->join('OUTER', $condition);

        return $this;
    }

    public function quote($text, $escape = true)
    {
        return $this->db->getWpDb()->quote($text, $escape);
    }

    public function quoteName($name, $as = null)
    {
        return $this->db->getWpDb()->quoteName($name, $as);
    }

    public function rightJoin($condition)
    {
        $this->join('RIGHT', $condition);

        return $this;
    }

    public function select($fields = '*'): DatabaseQueryInterface
    {
        $this->type = 'select';

        if (is_null($this->select)) {
            $this->select = new DatabaseQueryElement('SELECT', $fields);
        } else {
            $this->select->append($fields);
        }

        return $this;
    }

    public function set($conditions, $glue = ',')
    {
        if (is_null($this->set)) {
            $glue = strtoupper($glue);
            $this->set = new DatabaseQueryElement('SET', $conditions, "\n\t$glue ");
        } else {
            $this->set->append($conditions);
        }

        return $this;
    }

    public function setQuery($sql)
    {
        $this->sql = $sql;

        return $this;
    }

    public function update($table)
    {
        $this->type = 'update';
        $this->update = new DatabaseQueryElement('UPDATE', $table);

        return $this;
    }

    public function values($values)
    {
        if (is_null($this->values)) {
            $this->values = new DatabaseQueryElement('()', $values, '),(');
        } else {
            $this->values->append($values);
        }

        return $this;
    }

    public function where($column, string $operator = '=', $value = null): DatabaseQueryInterface
    {
        $conditions = "{$column} {$operator} {$value}";
        if (is_null($this->where)) {
            $this->where = new DatabaseQueryElement('WHERE', $conditions, ' AND ');

            return $this;
        }

        $this->where->append($conditions);

        return $this;
    }

    public function whereRaw($query, $glue = 'AND'): DatabaseQueryInterface
    {
        if (is_null($this->where)) {
            $this->where = new DatabaseQueryElement('WHERE', $query, $glue);

            return $this;
        }

        $this->where->append($query, $glue);

        return $this;
    }

    public function whereIn($column, $values): DatabaseQueryInterface
    {
        $values = implode(',', Arr::wrap($values));
        $this->where($column, 'IN', "( {$values} )");

        return $this;
    }

    public function extendWhere($outerGlue, $conditions, $innerGlue = 'AND')
    {
        // Replace the current WHERE with a new one which has the old one as an unnamed child.
        $this->where = new DatabaseQueryElement('WHERE', $this->where->setName('()'), " $outerGlue ");

        // Append the new conditions as a new unnamed child.
        $this->where->append(new DatabaseQueryElement('()', $conditions, " $innerGlue "));

        return $this;
    }

    public function orWhere($conditions, $glue = 'AND')
    {
        return $this->extendWhere('OR', $conditions, $glue);
    }

    public function andWhere($conditions, $glue = 'OR')
    {
        return $this->extendWhere('AND', $conditions, $glue);
    }

    public function __clone()
    {
        foreach ($this as $k => $v) {
            if ($k === 'db') {
                continue;
            }

            if (is_object($v) || is_array($v)) {
                $this->{$k} = unserialize(serialize($v));
            }
        }
    }

    public function union($query, $distinct = false, $glue = '')
    {
        // Set up the DISTINCT flag, the name with parentheses, and the glue.
        if ($distinct) {
            $name = 'UNION DISTINCT ()';
            $glue = ')' . PHP_EOL . 'UNION DISTINCT (';
        } else {
            $glue = ')' . PHP_EOL . 'UNION (';
            $name = 'UNION ()';
        }

        // Get the DatabaseQueryElement if it does not exist
        if (is_null($this->union)) {
            $this->union = new DatabaseQueryElement($name, $query, "$glue");
        }
        // Otherwise append the second UNION.
        else {
            $this->union->append($query);
        }

        return $this;
    }

    public function unionDistinct($query, $glue = '')
    {
        $distinct = true;

        // Apply the distinct flag to the union.
        return $this->union($query, $distinct, $glue);
    }

    public function format($format)
    {
        $query = $this;
        $args = array_slice(func_get_args(), 1);
        array_unshift($args, null);

        $i = 1;
        $func = function ($match) use ($query, $args, &$i) {
            if (isset($match[6]) && $match[6] == '%') {
                return '%';
            }

            // No argument required, do not increment the argument index.
            switch ($match[5]) {
                case 't':
                    return $query->currentTimestamp();

                    break;

                case 'z':
                    return $query->nullDate(false);

                    break;

                case 'Z':
                    return $query->nullDate(true);

                    break;
            }

            // Increment the argument index only if argument specifier not provided.
            $index = is_numeric($match[4]) ? (int) $match[4] : $i++;

            if (!$index || !isset($args[$index])) {
                // TODO - What to do? sprintf() throws a Warning in these cases.
                $replacement = '';
            } else {
                $replacement = $args[$index];
            }

            switch ($match[5]) {
                case 'a':
                    return 0 + $replacement;

                    break;

                case 'e':
                    return $query->escape($replacement);

                    break;

                case 'E':
                    return $query->escape($replacement, true);

                    break;

                case 'n':
                    return $query->quoteName($replacement);

                    break;

                case 'q':
                    return $query->quote($replacement);

                    break;

                case 'Q':
                    return $query->quote($replacement, false);

                    break;

                case 'r':
                    return $replacement;

                    break;

                    // Dates
                case 'y':
                    return $query->year($query->quote($replacement));

                    break;

                case 'Y':
                    return $query->year($query->quoteName($replacement));

                    break;

                case 'm':
                    return $query->month($query->quote($replacement));

                    break;

                case 'M':
                    return $query->month($query->quoteName($replacement));

                    break;

                case 'd':
                    return $query->day($query->quote($replacement));

                    break;

                case 'D':
                    return $query->day($query->quoteName($replacement));

                    break;

                case 'h':
                    return $query->hour($query->quote($replacement));

                    break;

                case 'H':
                    return $query->hour($query->quoteName($replacement));

                    break;

                case 'i':
                    return $query->minute($query->quote($replacement));

                    break;

                case 'I':
                    return $query->minute($query->quoteName($replacement));

                    break;

                case 's':
                    return $query->second($query->quote($replacement));

                    break;

                case 'S':
                    return $query->second($query->quoteName($replacement));

                    break;
            }

            return '';
        };

        /**
         * Regexp to find and replace all tokens.
         * Matched fields:
         * 0: Full token
         * 1: Everything following '%'
         * 2: Everything following '%' unless '%'
         * 3: Argument specifier and '$'
         * 4: Argument specifier
         * 5: Type specifier
         * 6: '%' if full token is '%%'
         */
        return preg_replace_callback('#%(((([\d]+)\$)?([aeEnqQryYmMdDhHiIsStzZ]))|(%))#', $func, $format);
    }

    public function dateAdd($date, $interval, $datePart)
    {
        return 'DATE_ADD(' . $date . ', INTERVAL ' . $interval . ' ' . $datePart . ')';
    }

    public function unionAll($query, $distinct = false, $glue = '')
    {
        $glue = ')' . PHP_EOL . 'UNION ALL (';
        $name = 'UNION ALL ()';

        // Get the DatabaseQueryElement if it does not exist
        if (is_null($this->unionAll)) {
            $this->unionAll = new DatabaseQueryElement($name, $query, "$glue");
        }
        // Otherwise append the second UNION.
        else {
            $this->unionAll->append($query);
        }

        return $this;
    }

    protected function validateRowNumber($orderBy, $orderColumnAlias)
    {
        if ($this->selectRowNumber) {
            throw new \RuntimeException("Method 'selectRowNumber' can be called only once per instance.");
        }

        $this->type = 'select';

        $this->selectRowNumber = [
            'orderBy' => $orderBy,
            'orderColumnAlias' => $orderColumnAlias,
        ];
    }

    public function selectRowNumber($orderBy, $orderColumnAlias)
    {
        $this->validateRowNumber($orderBy, $orderColumnAlias);
        $this->select("ROW_NUMBER() OVER (ORDER BY $orderBy) AS $orderColumnAlias");

        return $this;
    }

    public function get(): array
    {
        return $this->db->fetchAll($this);
    }

    public function orderBy(string $field, string $direction = 'ASC'): DatabaseQueryInterface
    {
        $this->order($field . ' ' . $direction);

        return $this;
    }
}
