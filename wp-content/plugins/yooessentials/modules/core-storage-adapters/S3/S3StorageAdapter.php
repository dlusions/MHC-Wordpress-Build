<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage\Adapter\S3;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Storage\StorageAdapter;
use ZOOlanders\YOOessentials\Storage\StorageConfigurationInvalidException;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\S3\Exception\NoSuchBucketException;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\S3\S3Client;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\AsyncAwsS3\AsyncAwsS3Adapter;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FilesystemAdapter;

class S3StorageAdapter extends StorageAdapter
{
    public function adapter(array $config = []): FilesystemAdapter
    {
        if ($config['account'] ?? null) {
            /** @var Auth $account */
            $account = $this->authManager()->auth($config['account']);
            $config = array_merge($account->withDecryptedKeys()->toArray(), $config);
        }

        $client = new S3Client([
            'accessKeyId' => $config['access_key_id'] ?? '',
            'accessKeySecret' => $config['access_key_secret'] ?? '',
            'region' => $config['region'] ?? '',
        ]);

        return new AsyncAwsS3Adapter(
            $client,
            // Bucket name
            $config['bucket'] ?? '',
            // Optional path prefix (we call it root)
            $config['root'] ?? ''
        );
    }

    public function validateConfig(array $config): void
    {
        $account = $config['account'] ?? null;
        $bucket = $config['bucket'] ?? null;
        $region = $config['region'] ?? null;

        if (!$account) {
            throw new StorageConfigurationInvalidException('Account is required.');
        }

        if (!$bucket) {
            throw new StorageConfigurationInvalidException('Bucket is required.');
        }

        if (!$region) {
            throw new StorageConfigurationInvalidException('Region is required.');
        }

        $auth = $this->authManager()->auth($account);
        if (!$auth) {
            throw new StorageConfigurationInvalidException('Cannot load Account.');
        }

        try {
            $client = new S3Client([
                'accessKeyId' => $auth->access_key_id ?? null,
                'accessKeySecret' => $auth->access_key_secret ?? null,
                'region' => $region,
            ]);

            $exists = $client
                ->bucketExists([
                    'Bucket' => $bucket,
                ])
                ->resolve();

            if (!$exists) {
                throw new StorageConfigurationInvalidException('Bucket does not exist in this region.');
            }

            // Weird, but this way we intercept any error when actually reading the list of files
            foreach ($this->adapter($config)->listContents('', false) as $file) {
                return;
            }
        } catch (NoSuchBucketException $e) {
            throw new StorageConfigurationInvalidException('Bucket does not exist in this region.');
        } catch (\Throwable $e) {
            throw new StorageConfigurationInvalidException('Unable to connect to S3: ' . $e->getMessage(), 400);
        }
    }

    private function authManager(): AuthManager
    {
        return app(AuthManager::class);
    }
}
