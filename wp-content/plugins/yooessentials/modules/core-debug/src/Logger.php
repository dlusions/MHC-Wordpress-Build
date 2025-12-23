<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Debug;

use ZOOlanders\YOOessentials\Data;

class Logger extends Data
{
    /**
     * @var array
     */
    public static $loggers = [];

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->entries = [];
    }

    public static function createLogger(string $id, string $group): self
    {
        self::$loggers[$group] ??= [];

        if (isset(self::$loggers[$group][$id])) {
            return self::$loggers[$group][$id];
        }

        return self::$loggers[$group][$id] = new Logger($id);
    }

    public function logEntry(string $id, array $args): void
    {
        $this->data['entries'][] = ['id' => $id] + $args;
    }
}
