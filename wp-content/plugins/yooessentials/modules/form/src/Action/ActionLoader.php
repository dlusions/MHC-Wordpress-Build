<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action;

use YOOtheme\Container;

class ActionLoader
{
    public function __invoke(Container $container, array $configs)
    {
        $container->extend(ActionManager::class, static function (ActionManager $manager, $app) use ($configs) {
            foreach ($configs as $classFiles) {
                foreach ($classFiles as $className => $configs) {
                    foreach ((array) $configs as $config) {
                        try {
                            $data = $app->config->loadFile($config);
                            $manager->addAction($data['name'], $className, $data);
                        } catch (\RuntimeException $e) {
                            if (class_exists($config)) {
                                $actionClass = $app($config);
                                if ($actionClass instanceof Action) {
                                    $manager->addAction($actionClass->name(), $config, $actionClass->getConfig());
                                }
                            }
                        }
                    }
                }
            }
        });
    }
}
