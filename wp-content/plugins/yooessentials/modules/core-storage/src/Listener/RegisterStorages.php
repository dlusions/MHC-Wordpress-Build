<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage\Listener;

use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Storage\StorageService;

class RegisterStorages
{
    public Config $config;
    public StorageService $storageService;

    public function __construct(Config $config, StorageService $storageService)
    {
        $this->config = $config;
        $this->storageService = $storageService;
    }

    public function handle(): void
    {
        $storages = $this->config->get(StorageService::STORAGES_CONFIG_KEY, []);
        $this->storageService->setConfigs($storages);
    }
}
