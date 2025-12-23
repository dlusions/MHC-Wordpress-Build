<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Filters;

class InvalidFilterException extends \RuntimeException
{
    public static function create(string $message, array $config): self
    {
        return new InvalidFilterException($message . ' - Configuration: ' . json_encode($config));
    }
}
