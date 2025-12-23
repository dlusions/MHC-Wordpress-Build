<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition;

use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;
use ZOOlanders\YOOessentials\Debug\Logger;

class ConditionResolver
{
    protected ?Logger $logger = null;
    protected ConditionManager $manager;
    protected SourceResolverManager $sourceResolver;

    protected array $rules;
    protected string $query = '';

    public function __construct(array $rules)
    {
        $this->manager = app(ConditionManager::class);
        $this->sourceResolver = app(SourceResolverManager::class);

        // filter out orphan conditions
        $this->rules = array_filter($rules, fn ($rule) => $this->manager->rule($rule->type));
    }

    public function query(): string
    {
        return $this->query;
    }

    public function withLogger(Logger $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function withQuery(string $query): self
    {
        $this->query = $this->validateQuery($query);

        return $this;
    }

    public function resolve(object $node, array $params = []): bool
    {
        $resolved = $this->resolveRules($node, $params);
        $result = $this->resolveQuery($resolved);

        if ($this->logger !== null) {
            $this->logger->result = $result;
        };

        return $result;
    }

    private function resolveRules(object $node, array $params): array
    {
        return array_map(function ($rule) use ($node, $params) {
            $id = $rule->id ?? uniqid();

            $rule->props = (array) $rule->props;

            $result = (object) [
                'result' => true,
                'status' => $rule->props['status'] ?? ''
            ];

            // support manualy disabled rule
            if ($result->status === 'disabled') {
                return $result->result;
            }

            try {
                self::resolveRule($result, $rule, $node, $params);
            } catch (\Throwable $e) {
                $result->result = false;
                $result->error = $e->getMessage();
            }

            if ($this->logger) {
                $ruleType = $this->manager->rule($rule->type);
                $rawProps = $result->props;

                $result->props = array_merge(
                    $this->resolvePropsLabels($ruleType, (array) $rawProps),
                    $ruleType->logArgs($rawProps)
                );

                $this->logger && $this->logger->logEntry($id, (array) $result);
            }

            return $result->result;
        }, $this->rules);
    }

    private function resolveRule(object &$result, object $rule, object $node, array $params): void
    {
        // resolve dynamic props
        $rule = (object) $rule;
        $rule->props = (array) $rule->props;
        $rule->source_extended = json_decode(json_encode($rule->source_extended ?? []));

        $this->sourceResolver->resolveAdjacentProps($rule, $node, $params);

        $result->props = (object) $rule->props;

        // resolve props
        $ruleType = $this->manager->rule($rule->type);
        $result->props = (object) array_merge(
            (array) $result->props,
            (array) $ruleType->resolveProps($result->props, $node)
        );

        $status = $rule->props['status'] ?? true;
        $reversed = $rule->props['reversed'] ?? false;

        // support dynamically disabled rule
        if ($status === false) {
            return;
        }

        $result->result = $ruleType->resolve($result->props, $node);

        if ($reversed) {
            $result->result = !$result->result;
        }
    }

    private function resolveQuery(array $resolved): bool
    {
        if (count($resolved) === 1) {
            return (bool) array_shift($resolved);
        }

        if ($this->query === ConditionManager::MODE_OR) {
            return Arr::some($resolved, fn ($v) => $v);
        }

        if ($this->query === ConditionManager::MODE_AND) {
            return Arr::every($resolved, fn ($v) => $v);
        }

        $result = false;
        $query = $this->query;

        // replace {n} refs
        foreach ($resolved as $i => $state) {
            $query = preg_replace('/\{' . ($i + 1) . '\}/', $state ? '1' : '0', $query);
        }

        // set as 1 non resolved ones
        $query = preg_replace('/{\d}/', '0', $query);

        // avoid possible wrong syntax
        if (substr_count($query, '(') !== substr_count($query, ')')) {
            return $result;
        }

        if (empty($query)) {
            return $result;
        }

        eval("\$result = $query;");

        return $result;
    }

    private function validateQuery(string $query): string
    {
        if (count($this->rules) === 1) {
            return '';
        }

        if ($query === ConditionManager::MODE_AND || $query === ConditionManager::MODE_OR) {
            return $query;
        }

        // remove all spaces
        $query = str_replace(' ', '', $query);

        // remove invalid syntax
        $query = preg_replace('/\(OR\)|\(AND\)/', '', $query);
        $query = preg_replace('/^OR$|^AND$/', '', $query);

        // replace operators
        $query = str_replace(['AND', 'OR'], ['&&', '||'], $query);

        // remove unsupported characters
        $query = preg_replace('/[^0-9&&||(){}]+/', '', $query);

        return $query;
    }

    private function resolvePropsLabels(ConditionRuleInterface $rule, array $args): array
    {
        $result = [];
        $fields = $rule->fields();

        foreach ($args as $key => $value) {
            $field = $fields[$key] ?? [];
            $type = $field['type'] ?? '';
            $label = $field['label'] ?? Str::upperFirst($key);

            if ($key === 'status' || $key === 'name') {
                continue;
            }

            if (is_string($value) && $type === 'select' and $field['options'] ?? null) {
                $value = array_search($value, $field['options']);
            }

            if ($value instanceof \DateTime || $value instanceof \DateTimeImmutable) {
                $value = $value->format('r');
            }

            $result[$label] = $value;
        }

        return $result;
    }
}
