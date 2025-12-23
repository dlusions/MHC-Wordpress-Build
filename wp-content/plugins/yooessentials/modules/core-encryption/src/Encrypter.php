<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption;

interface Encrypter
{
    /**
     * Encrypts data with given parameters.
     *
     * @param mixed $data
     * @param bool  $serialize
     *
     * @return string
     */
    public function encrypt($data, bool $serialize = true): string;

    /**
     * Decrypts data with given parameters.
     *
     * @param string $data
     * @param bool   $serialize
     *
     * @return bool|mixed
     */
    public function decrypt(string $data, bool $serialize = true);
}
