<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

interface Session
{
    public function has($name);

    public function get($name, $default = null);

    public function set($name, $value);

    public function clear($name);

    public function start();

    public function close();
}
