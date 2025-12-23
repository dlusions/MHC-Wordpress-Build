<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition;

interface ConditionRuleInterface
{
    public function name(): string;

    public function title(): string;

    public function group(): string;

    public function collection(): string;

    public function collectionDescription(): string;

    public function icon(): string;

    public function namespace(): string;

    public function description(): string;

    public function docs(): string;

    public function fields(): array;

    public function resolveProps(object $props, object $node): object;

    public function logArgs(object $props): array;

    /**
     * @param $props The settings values from the rule fields
     * @param $node The current element node being evaluated
     */
    public function resolve(object $props, object $node): bool;
}
