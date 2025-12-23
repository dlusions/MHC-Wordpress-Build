<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage;

class StorageService
{
    public const STORAGES_CONFIG_KEY = 'storages';

    protected array $configs = [];

    protected StorageAdapterManager $manager;

    public function __construct(StorageAdapterManager $manager)
    {
        $this->manager = $manager;
    }

    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;

        return $this;
    }

    /**
     * @return Storage[]
     */
    public function storages(): array
    {
        return array_map(function (array $config) {
            $adapter = $this->manager->adapter($config['adapter'] ?? '');

            return $adapter->storage($config);
        }, $this->configs());
    }

    public function storage(string $id): ?Storage
    {
        $storage = array_filter($this->storages(), fn (Storage $storage) => $storage->id() === $id);

        if (empty($storage)) {
            return null;
        }

        return array_shift($storage);
    }

    /**
     * @return array[]|array
     */
    public function configs(?string $name = null): array
    {
        if (!$name) {
            return $this->configs;
        }

        return array_filter($this->configs, fn (array $storage) => ($storage['adapter'] ?? '') === $name);
    }
}
