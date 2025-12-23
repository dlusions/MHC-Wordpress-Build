<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

/** Cloned from Joomla's query element class */
class DatabaseQueryElement
{
    protected $name = null;
    protected array $elements;
    protected $glue = null;

    public function __construct($name, $elements, $glue = ',')
    {
        $this->elements = [];
        $this->name = $name;
        $this->glue = $glue;

        $this->append($elements);
    }

    public function __toString()
    {
        if (substr($this->name, -2) == '()') {
            return PHP_EOL . substr($this->name, 0, -2) . '(' . implode($this->glue, $this->elements) . ')';
        }

        return PHP_EOL . $this->name . ' ' . implode($this->glue, $this->elements);
    }

    public function append($elements): self
    {
        if (is_array($elements)) {
            $this->elements = array_merge($this->elements, $elements);

            return $this;
        }

        $this->elements[] = $elements;

        return $this;
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __clone()
    {
        foreach ($this as $k => $v) {
            if (is_object($v) || is_array($v)) {
                $this->{$k} = unserialize(serialize($v));
            }
        }
    }
}
