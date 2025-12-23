<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption\Library;

class McryptLibrary extends Library
{
    public const MODE = MCRYPT_MODE_CBC;

    public const CIPHER = MCRYPT_RIJNDAEL_128;

    /**
     * @inheritdoc
     */
    public function encrypt($data, $key, $iv)
    {
        return mcrypt_encrypt(static::CIPHER, $key, $data, static::MODE, $iv);
    }

    /**
     * @inheritdoc
     */
    public function decrypt($data, $key, $iv)
    {
        return mcrypt_decrypt(static::CIPHER, $key, $data, static::MODE, $iv);
    }

    /**
     * @inheritdoc
     */
    public function generateIv()
    {
        return mcrypt_create_iv(mcrypt_get_iv_size(static::CIPHER, static::MODE), MCRYPT_DEV_URANDOM);
    }
}
