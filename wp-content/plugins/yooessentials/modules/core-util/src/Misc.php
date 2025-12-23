<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

abstract class Misc
{
    /**
     * Compiles a parsable string representation of a value.
     *
     * @param mixed    $value
     * @param callable $callback
     * @param int      $indent
     *
     * @return string
     */
    public static function compileValue($value, ?callable $callback = null, $indent = 0)
    {
        if (is_array($value)) {
            $array = [];
            $assoc = array_values($value) !== $value;
            $indention = str_repeat('  ', $indent);
            $indentlast = $assoc ? "\n" . $indention : '';

            foreach ($value as $key => $val) {
                $array[] =
                    ($assoc ? "\n  " . $indention . var_export($key, true) . ' => ' : '') .
                    self::compileValue($val, $callback, $indent + 1);
            }

            return '[' . join(', ', $array) . $indentlast . ']';
        }

        return $callback ? $callback($value) : var_export($value, true);
    }

    /**
     * Resolve a callable and get its class name
     *
     * @param callable $callable
     * @return string|null
     */
    public static function getCallableClassName(callable $callable): ?string
    {
        if (is_array($callable)) {
            return is_object($callable[0]) ? get_class($callable[0]) : $callable[0];
        }

        if ($callable instanceof \Closure) {
            $reflection = new \ReflectionFunction($callable);
            $class = $reflection->getClosureScopeClass();

            return $class ? $class->getName() : null;
        }

        return null;
    }
}
