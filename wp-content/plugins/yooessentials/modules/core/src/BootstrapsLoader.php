<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

use YOOtheme\Application;
use YOOtheme\Container;
use YOOtheme\Event;
use YOOtheme\File;

class BootstrapsLoader
{
    public function __invoke(Container $container, array $configs)
    {
        $container->extend(Application::class, static function (Application $app) use ($configs) {
            foreach ($configs as $files) {
                foreach ($files as $file) {
                    if (!File::exists($file)) {
                        Event::emit('yooessentials.error', [
                            'addon' => 'core',
                            'file' => $file,
                            'error' => 'Error loading bootstrap file, file does not exist.',
                        ]);

                        continue;
                    }

                    $app->load($file);
                }
            }
        });
    }
}
