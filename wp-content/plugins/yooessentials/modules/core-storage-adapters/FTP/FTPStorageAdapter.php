<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage\Adapter\FTP;

use ZOOlanders\YOOessentials\Storage\StorageAdapter;
use ZOOlanders\YOOessentials\Storage\StorageConfigurationInvalidException;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FilesystemAdapter;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FilesystemException;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\Ftp\FtpAdapter;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\Ftp\FtpConnectionOptions;

class FTPStorageAdapter extends StorageAdapter
{
    public function adapter(array $config = []): FilesystemAdapter
    {
        // set required defaults
        $config['host'] ??= '127.0.0.1';
        $config['root'] = $this->fixRoot($config['root'] ?? '/');
        $config['username'] ??= '';
        $config['password'] ??= '';
        $config['ignorePassiveAddress'] = $config['ignore_passive_address'] ?? null;
        $config['port'] = (int) ($config['port'] ?? 21);

        return new FtpAdapter(FtpConnectionOptions::fromArray($config));
    }

    public function validateConfig(array $config): void
    {
        $host = $config['host'] ?? null;
        $root = $config['root'] ?? '';

        if (!$host) {
            throw new StorageConfigurationInvalidException('Host is required.');
        }

        // FTP throws a warning when changing directory. Let's catch that
        // to see if the root dir exists
        $rootDirExists = true;
        set_error_handler(function () use (&$rootDirExists) {
            $rootDirExists = false;
        }, E_WARNING);

        try {
            // Weird, but this way we intercept any error when actually reading the list of files
            foreach ($this->adapter($config)->listContents($root, false) as $file) {
                restore_error_handler();

                return;
            }
        } catch (FilesystemException $e) {
            throw new StorageConfigurationInvalidException($e->getMessage());
        }

        restore_error_handler();

        if (!$rootDirExists) {
            throw new StorageConfigurationInvalidException('Directory does not exist.');
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
