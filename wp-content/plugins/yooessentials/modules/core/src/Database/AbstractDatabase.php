<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Database;

abstract class AbstractDatabase implements Database
{
    public const SINGLE_QUOTED_TEXT = '\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\'';

    public const DOUBLE_QUOTED_TEXT = '"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"';

    public string $driver;

    public string $prefix;

    /**
     * The table prefix placeholder.
     */
    public string $placeholder = '@';

    /**
     * The regex for parsing SQL query parts.
     */
    protected array $regex = [];

    /**
     * Cache class reflections.
     */
    protected array $reflClasses = [];

    /**
     * Cache class reflection properties.
     */
    protected array $reflFields = [];

    /**
     * Gets the table prefix.
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Replaces the table prefix placeholder with actual one.
     */
    public function replacePrefix(string $query): string
    {
        $offset = 0;
        $length = strlen($this->prefix) - strlen($this->placeholder);

        foreach ($this->getUnquotedQueryParts($query) as $part) {
            if (!str_contains($part[0], $this->placeholder)) {
                continue;
            }

            $replace = preg_replace($this->regex['placeholder'], $this->prefix . '$1', $part[0], -1, $count);

            if ($count) {
                $query = substr_replace($query, $replace, $part[1] + $offset, strlen($part[0]));
                $offset += $length;
            }
        }

        return $query;
    }

    /**
     * @inheritdoc
     *
     * @throws \ReflectionException
     */
    public function fetchObject($statement, array $params = [], $class = 'stdClass', $args = [])
    {
        if ($row = $this->fetchAssoc($statement, $params)) {
            return $this->hydrate($class, $row, $args);
        }

        return null;
    }

    /**
     * @inheritdoc
     *
     * @throws \ReflectionException
     */
    public function fetchAllObjects($statement, array $params = [], $class = 'stdClass', $args = [])
    {
        $result = [];

        foreach ($this->fetchAll($statement, $params) as $row) {
            $result[] = $this->hydrate($class, $row, $args);
        }

        return $result;
    }

    /**
     * Parses the unquoted SQL query parts.
     */
    protected function getUnquotedQueryParts(string $query): array
    {
        if (!$this->regex) {
            $this->regex['quotes'] = "/([^'\"]+)(?:" . self::DOUBLE_QUOTED_TEXT . '|' . self::SINGLE_QUOTED_TEXT . ')?/As';
            $this->regex['placeholder'] = '/' . preg_quote($this->placeholder) . '([a-zA-Z_][a-zA-Z0-9_]*)/';
        }

        preg_match_all($this->regex['quotes'], $query, $parts, PREG_OFFSET_CAPTURE);

        return $parts[1];
    }

    /**
     * Prepares a parametrized SQL query string.
     */
    protected function prepareQuery(string $statement, array $params = []): string
    {
        $parameters = [];

        foreach ($params as $key => $value) {
            $parameters[substr($key, 0, 1) !== ':' ? ":$key" : $key] = $this->prepareValue($value);
        }

        return strtr($this->replacePrefix($statement), $parameters);
    }

    /**
     * Prepares a parameter value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function prepareValue($value)
    {
        if (is_string($value)) {
            return $this->escape($value);
        }

        if (is_array($value)) {
            return join(',', array_map([$this, 'prepareValue'], $value));
        }

        if (is_null($value)) {
            return 'NULL';
        }

        return $value;
    }

    /**
     * Creates object from data.
     *
     * @throws \ReflectionException
     */
    protected function hydrate(string $class, array $data, array $args = []): object
    {
        if ('stdClass' === $class) {
            return (object) $data;
        }

        $reflClass = $this->getReflectionClass($class);
        $reflFields = $this->getReflectionFields($class);

        $values = array_intersect_key($data, $reflFields);
        $instance = $reflClass->newInstanceWithoutConstructor();

        foreach ($values as $key => $value) {
            $reflFields[$key]->setValue($instance, $value);
        }

        if ($constructor = $reflClass->getConstructor()) {
            $constructor->invokeArgs($instance, $args);
        }

        return $instance;
    }

    /**
     * Gets ReflectionClass for given class name.
     *
     * @throws \ReflectionException
     */
    protected function getReflectionClass(string $class): \ReflectionClass
    {
        if (!isset($this->reflClasses[$class])) {
            $this->reflClasses[$class] = new \ReflectionClass($class);
        }

        return $this->reflClasses[$class];
    }

    /**
     * Gets ReflectionProperty array for given class name.
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionProperty[]
     */
    protected function getReflectionFields(string $class)
    {
        if (!isset($this->reflFields[$class])) {
            $this->reflFields[$class] = [];

            foreach ($this->getReflectionClass($class)->getProperties() as $property) {
                $property->setAccessible(true);
                $this->reflFields[$class][$property->getName()] = $property;
            }
        }

        return $this->reflFields[$class];
    }

    /**
     * @inheritdoc
     */
    public function quote($text)
    {
        if (is_array($text)) {
            foreach ($text as $k => $v) {
                $text[$k] = $this->quote($v);
            }

            return $text;
        }

        if (is_numeric($text)) {
            if (is_float($text)) {
                return (float) $text;
            }

            return (int) $text;
        }

        return $this->escape($text);
    }

    /**
     * @inheritdoc
     */
    public function quoteNameStr(array $strArr): string
    {
        $parts = [];
        $q = '`';

        foreach ($strArr as $part) {
            if (is_null($part)) {
                continue;
            }

            if (strlen($q) == 1) {
                $parts[] = $q . str_replace($q, $q . $q, $part) . $q;
            } else {
                $parts[] = $q[0] . str_replace($q[1], $q[1] . $q[1], $part) . $q[1];
            }
        }

        return implode('.', $parts);
    }
}
