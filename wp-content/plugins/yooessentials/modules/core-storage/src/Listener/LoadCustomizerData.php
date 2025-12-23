<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage\Listener;

use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use ZOOlanders\YOOessentials\Storage\StorageAdapterManager;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;
    public StorageAdapterManager $manager;

    public function __construct(Config $config, Metadata $metadata, StorageAdapterManager $manager)
    {
        $this->config = $config;
        $this->metadata = $metadata;
        $this->manager = $manager;
    }

    public function handle(): void
    {
        $adapters = [];
        foreach ($this->manager->adapters() as $class) {
            $config = $class->metadata();
            $adapters[$config->name] = (array) $config;
        }

        $this->config->set('yooessentials.customizer.storage_adapters', $adapters);
        $this->config->addFile('customizer', Path::get('../../config/customizer.json'));
    }
}
