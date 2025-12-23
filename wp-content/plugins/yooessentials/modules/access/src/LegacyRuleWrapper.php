<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access;

use ZOOlanders\YOOessentials\Condition\ConditionRuleInterface;

class LegacyRuleWrapper implements ConditionRuleInterface
{
    private LegacyRuleInterface $rule;

    public function __construct(LegacyRuleInterface $rule)
    {
        $this->rule = $rule;
    }

    public function name(): string
    {
        return $this->rule->namespace();
    }

    public function title(): string
    {
        return $this->rule->name();
    }

    public function group(): string
    {
        return $this->rule->namespace();
    }

    public function collection(): string
    {
        return '';
    }

    public function collectionDescription(): string
    {
        return '';
    }

    public function icon(): string
    {
        if (method_exists($this->rule, 'icon')) {
            return $this->rule->icon();
        }

        return '';
    }

    public function namespace(): string
    {
        return $this->rule->namespace();
    }

    public function description(): string
    {
        return $this->rule->description();
    }

    public function docs(): string
    {
        if (method_exists($this->rule, 'docs')) {
            return $this->rule->docs();
        }

        return '';
    }

    public function fields(): array
    {
        return $this->rule->fields();
    }

    public function resolveProps(object $props, object $node): object
    {
        return $props;
    }

    public function logArgs(object $props): array
    {
        return [];
    }

    public function resolve(object $props, object $node): bool
    {
        return $this->rule->resolve($props, $node);
    }
}
