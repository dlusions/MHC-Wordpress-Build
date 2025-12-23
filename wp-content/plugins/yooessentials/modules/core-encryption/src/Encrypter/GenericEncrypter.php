<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption\Encrypter;

use ZOOlanders\YOOessentials\Encryption\Encrypter;
use ZOOlanders\YOOessentials\Encryption\Library\Library;
use ZOOlanders\YOOessentials\Encryption\Library\McryptLibrary;
use ZOOlanders\YOOessentials\Encryption\Library\OpenSSLLibrary;

class GenericEncrypter implements Encrypter
{
    protected Library $library;

    protected string $cipherKey;

    protected string $hashKey;

    protected string $hashAlgo = 'sha256';

    /**
     * Constructor.
     *
     * @throws \RuntimeException
     */
    public function __construct(string $password, string $salt = '')
    {
        if (is_callable('openssl_encrypt')) {
            $this->library = new OpenSSLLibrary();
        } elseif (is_callable('mcrypt_encrypt')) {
            $this->library = new McryptLibrary();
        } else {
            throw new \RuntimeException('Encryption not supported. Install OpenSSL or Mcrypt.');
        }

        [$this->cipherKey, $this->hashKey] = static::generateKeys($this->hashAlgo, $password, $salt);
    }

    /**
     * @inheritdoc
     */
    public function encrypt($data, $serialize = true): string
    {
        $iv = $this->library->generateIv();
        $data = $this->library->encrypt($serialize ? serialize($data) : $data, $this->cipherKey, $iv);
        $hash = hash_hmac($this->hashAlgo, $iv . $data, $this->hashKey);
        $encoded = array_map('base64_encode', [$iv, $data, $hash]);

        return implode('.', $encoded);
    }

    /**
     * @inheritdoc
     */
    public function decrypt($data, $serialize = true): string
    {
        $encoded = explode('.', $data);

        if (count($encoded) !== 3) {
            return false;
        }

        [$iv, $data, $hash] = array_map('base64_decode', $encoded);

        if (hash_hmac($this->hashAlgo, $iv . $data, $this->hashKey) !== $hash) {
            return false;
        }

        $data = $this->library->decrypt($data, $this->cipherKey, $iv);

        return $serialize ? unserialize($data) : $data;
    }

    /**
     * Generates a PBKDF2 key derivation of a supplied password.
     */
    public static function pbkdf2(
        string $algorithm,
        string $password,
        string $salt,
        int $iterations,
        int $length = 0,
        bool $raw_output = false
    ): string {
        // With PHP 7.4+ Hash extension is always available
        if (!$raw_output) {
            $length *= 2;
        }

        return hash_pbkdf2($algorithm, $password, $salt, $iterations, $length, $raw_output);
    }

    /**
     * Generates a cipher and a hash key using PBKDF2.
     */
    protected static function generateKeys(string $algorithm, string $password, string $salt): array
    {
        $key = static::pbkdf2($algorithm, $password, $salt, strlen($password ?? '') >= 32 ? 1 : 1000, 32, true);

        return [substr($key, 0, 16), substr($key, 16)];
    }
}
