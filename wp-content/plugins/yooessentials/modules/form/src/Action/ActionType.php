<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action;

use YOOtheme\Arr;

class ActionType implements \JsonSerializable
{
    public array $data;

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
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Checks if a data value exists.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Handles method calls.
     *
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($name, array $args)
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
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return Arr::omit($this->data, ['path']);
    }
}
