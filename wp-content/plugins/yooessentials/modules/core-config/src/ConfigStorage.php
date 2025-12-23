<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Config;

use YOOtheme\Arr;

abstract class ConfigStorage implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * Gets a value (shortcut)
     */
    public function __invoke(string $key, $default = null)
    {
        return $this->get($key, $default);
    }

    /**
     * Checks if a key exists
     */
    public function has(string $key): bool
    {
        return Arr::has($this->values, $key);
    }

    /**
     * Gets a value
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->values, $key, $default);
    }

    /**
     * Sets a value
     */
    public function set(string $key, $value)
    {
        Arr::set($this->values, $key, $value);

        return $this;
    }

    /**
     * Deletes a value.
     */
    public function del(string $key)
    {
        Arr::del($this->values, $key);

        return $this;
    }

    /**
     * Merges values
     */
    public function add(array $values)
    {
        $this->values = Arr::merge($this->values, $values);

        return $this;
    }

    /**
     * Replaces all values
     */
    public function replace(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Gets values which should be serialized to JSON.
     */
    public function jsonSerialize(): array
    {
        return $this->values;
    }

    /**
     * Gets values which should be converted to array.
     */
    public function toArray(): array
    {
        return $this->values;
    }
}
