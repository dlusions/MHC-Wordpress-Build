<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition;

use function YOOtheme\app;
use YOOtheme\Container;

class ConditionRuleLoader
{
    public function __invoke(Container $container, array $configs)
    {
        $container->extend(ConditionManager::class, static function (ConditionManager $manager) use ($configs) {
            foreach ($configs as $classFiles) {
                foreach ($classFiles as $className => $rules) {
                    foreach ((array) $rules as $rule) {
                        $data = app()->config->loadFile($rule);
                        $manager->addRule($data['name'], new $className($data));
                    }
                }
            }
        });
    }
}
