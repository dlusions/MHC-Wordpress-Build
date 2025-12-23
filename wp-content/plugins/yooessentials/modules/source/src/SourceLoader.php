<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source;

use YOOtheme\Container;

class SourceLoader
{
    public function __invoke(Container $container, array $configs)
    {
        $container->extend(SourceService::class, static function (SourceService $service) use ($container, $configs) {
            foreach ($configs as $config) {
                foreach ($config as $type => $class) {
                    $service->addSourceType($type, $class);
                }
            }
        });
    }
}
