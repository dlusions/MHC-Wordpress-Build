<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition;

class ConditionManager
{
    public const MODE_OR = 'OR';
    public const MODE_AND = 'AND';
    public const MODE_CUSTOM = 'custom';

    /** @var ConditionRuleInterface[] */
    protected array $rules = [];

    public function addRule(string $name, ConditionRuleInterface $rule): self
    {
        $this->rules[$name] = $rule;

        return $this;
    }

    public function rule(string $name): ?ConditionRuleInterface
    {
        return $this->rules[$name] ?? null;
    }

    public function rules(): array
    {
        return $this->rules;
    }
}
