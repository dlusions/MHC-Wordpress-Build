<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption\Library;

abstract class Library
{
    /**
     * Encrypts data with given parameters.
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     *
     * @return string
     */
    abstract public function encrypt($data, $key, $iv);

    /**
     * Decrypts data with given parameters.
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     *
     * @return string
     */
    abstract public function decrypt($data, $key, $iv);

    /**
     * Generates an initialization vector.
     *
     * @return string
     */
    abstract public function generateIv();
}
