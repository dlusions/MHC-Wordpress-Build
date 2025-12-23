<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage\Adapter\FTP;

use YOOtheme\Path;
use ZOOlanders\YOOessentials\Storage\StorageAdapter;
use ZOOlanders\YOOessentials\Storage\StorageConfigurationInvalidException;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FilesystemAdapter;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FilesystemException;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\PhpseclibV2\SftpAdapter;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\PhpseclibV2\SftpConnectionProvider;

class SFTPStorageAdapter extends StorageAdapter
{
    public function adapter(array $config = []): FilesystemAdapter
    {
        $privateKey = ($config['privateKey'] ?? null) ? Path::resolve('~/' . $config['privateKey']) : null;

        return new SftpAdapter(
            new SftpConnectionProvider(
                $config['host'] ?? '127.0.0.1', // host (required)
                $config['username'] ?? '', // username (required)
                $config['password'] ?? null, // password (optional, default: null) set to null if privateKey is used
                $privateKey, // private key (optional, default: null) can be used instead of password, set to null if password is set
                $config['passphrase'] ?? null, // passphrase (optional, default: null), set to null if privateKey is not used or has no passphrase
                $config['port'] ?? 22, // port (optional, default: 22)
            ),
            $this->fixRoot($config['root'] ?? ''), // root path (required)
        );
    }

    public function validateConfig(array $config): void
    {
        $host = $config['host'] ?? null;
        $username = $config['username'] ?? null;
        $root = $config['root'] ?? '';

        if (!$host) {
            throw new StorageConfigurationInvalidException('Host is required.');
        }

        if (!$username) {
            throw new StorageConfigurationInvalidException('Username is required.');
        }

        // FTP throws a warning when changing directory. Let's catch that
        // to see if the root dir exists
        $errors = [];
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$errors) {
            $errors[] = $errstr;
        });

        try {
            // Weird, but this way we intercept any error when actually reading the list of files
            foreach ($this->adapter($config)->listContents($root, false) as $file) {
                restore_error_handler();

                return;
            }

            if ($errors) {
                throw new StorageConfigurationInvalidException(implode(', ', $errors));
            }

            return;
        } catch (FilesystemException $e) {
            throw new StorageConfigurationInvalidException($errors ? implode(', ', $errors) : $e->getMessage());
        }
    }

    private function fixRoot($root): string
    {
        if (strlen(trim($root)) <= 0) {
            $root = '';
        }

        if (substr($root, 0, 1) === '/') {
            $root = substr($root, 1);
        }

        return $root;
    }
}
