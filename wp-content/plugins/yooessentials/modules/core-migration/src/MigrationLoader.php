<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration;

use YOOtheme\Container;
use YOOtheme\Event;

class MigrationLoader
{
    public function __invoke(Container $container, array $configs)
    {
        $container->extend(MigrationService::class, static function (MigrationService $service, $app) use ($configs) {
            foreach ($configs as $classFiles) {
                foreach ($classFiles as $className) {
                    try {
                        $service->addMigration($className);
                    } catch (\InvalidArgumentException $e) {
                        Event::emit('yooessentials.error', [
                            'addon' => 'core-migration',
                            'migration' => $className,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        });
    }
}
