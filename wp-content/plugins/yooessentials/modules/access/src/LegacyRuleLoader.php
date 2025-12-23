<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access;

use YOOtheme\Container;
use ZOOlanders\YOOessentials\Condition\ConditionManager;

class LegacyRuleLoader
{
    public function __invoke(Container $container, array $configs)
    {
        $container->extend(ConditionManager::class, static function (ConditionManager $manager, $app) use ($configs) {
            foreach ($configs as $classes) {
                foreach ($classes as $class) {
                    /** @var LegacyRuleInterface $ruleInstance */
                    $ruleInstance = $app($class);

                    $wrapper = new LegacyRuleWrapper($ruleInstance);
                    $manager->addRule($ruleInstance->namespace(), $wrapper);
                }
            }
        });
    }
}
