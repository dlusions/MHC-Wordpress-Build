<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use ZOOlanders\YOOessentials\Access\LegacyAccessRule;
use ZOOlanders\YOOessentials\Access\LegacyRuleInterface;

// 3rd party legacy compatibility
class_alias(LegacyAccessRule::class, '\\ZOOlanders\\YOOessentials\\Access\\AbstractRule');
class_alias(
    LegacyRuleInterface::class,
    '\\ZOOlanders\\YOOessentials\\Access\\RuleInterface'
);
class_alias(LegacyRuleInterface::class, '\\ZOOlanders\\YOOessentials\\Access\\AccessRule');
