<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage;

use YOOtheme\Config;
use YOOtheme\Container;

class StorageAdapterLoader
{
    public function __invoke(Container $container, array $configs)
    {
        $config = $container(Config::class);
        $container->extend(StorageAdapterManager::class, static function (StorageAdapterManager $manager) use (
            $container,
            $configs,
            $config
        ) {
            foreach ($configs as $classFiles) {
                foreach ($classFiles as $className => $adapters) {
                    foreach ((array) $adapters as $adapter) {
                        $data = $config->loadFile($adapter);
                        $manager->addAdapter($data['name'], $className, $data);
                    }
                }
            }
        });
    }
}
