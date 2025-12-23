<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access;

/**
 * The old RuleInterface, kept for backward compatibility and aliased
 */
interface LegacyRuleInterface
{
    public function name(): string;

    public function group(): string;

    public function namespace(): string;

    public function description(): string;

    public function fields(): array;

    /**
     * @param $props The settings values from the rule fields
     * @param $node The current element node being evaluated
     */
    public function resolve(object $props, object $node): bool;
}
