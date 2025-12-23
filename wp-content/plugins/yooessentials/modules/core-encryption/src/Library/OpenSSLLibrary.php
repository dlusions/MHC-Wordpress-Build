<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption\Library;

class OpenSSLLibrary extends Library
{
    public const CIPHER = 'AES-128-CBC';

    /**
     * @inheritdoc
     */
    public function encrypt($data, $key, $iv)
    {
        return openssl_encrypt($data, static::CIPHER, $key, 0, $iv);
    }

    /**
     * @inheritdoc
     */
    public function decrypt($data, $key, $iv)
    {
        return openssl_decrypt($data, static::CIPHER, $key, 0, $iv);
    }

    /**
     * @inheritdoc
     */
    public function generateIv()
    {
        return openssl_random_pseudo_bytes(openssl_cipher_iv_length(static::CIPHER));
    }
}
