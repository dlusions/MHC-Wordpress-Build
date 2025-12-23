<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

class Data implements \JsonSerializable
{
    public array $data = [];

    /**
     * Constructor.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Gets a data value.
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Sets a data value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function __set(string $key, $value)
    {
        return $this->data[$key] = $value;
    }

    /**
     * Checks if a data value exists.
     *
     * @return mixed
     */
    public function __isset(string $key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Handles method calls.
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        $method = $this->$name;

        if (!is_callable($method)) {
            trigger_error(sprintf('Call to undefined method %s::%s()', __CLASS__, $name), E_USER_ERROR);
        }

        if ($method instanceof \Closure) {
            $method = $method->bindTo($this);
        }

        return call_user_func_array($method, $args);
    }

    /**
     * Returns data for JSON serialize.
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * Returns data array.
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
