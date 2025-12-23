<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Resolvers;

use function YOOtheme\app;
use YOOtheme\Arr;
use YOOtheme\Event;
use YOOtheme\Config;
use ZOOlanders\YOOessentials\Debug\Logger;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverAbstract;
use ZOOlanders\YOOessentials\Condition\ConditionManager;
use ZOOlanders\YOOessentials\Condition\ConditionResolver;

class ComposedSourceResolver extends SourceResolverAbstract
{
    const SOURCE_REGEX = '/{{ sources\.(\w{4}) }}/';
    const CONDITION_REGEX = '/{{ conditions\.(\w{4}) }}(.*?){{ \/ }}/';

    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function preload(object $source, object $node, array $params)
    {
        if (!self::isComposed($source)) {
            return;
        }

        foreach (self::getSources($source) as $src) {
            $this->manager->preload((object) $src, $node, $params);
        }

        foreach (self::getConditions($source) as $condition) {
            foreach ($condition->rules ?? [] as $rule) {
                $props = (array) ($rule->source_extended->props ?? []);

                foreach ($props as $src) {
                    $this->manager->preload((object) $src, $node, $params);
                }
            }
        }
    }

    public function resolve(object $source, object $node, array $params)
    {
        if (!self::isComposed($source)) {
            return null;
        }

        $value = $source->composed->value ?? null;

        if (!$value) {
            return null;
        }

        $value = $this->resolveConditions($value, $source, $node, $params);
        $value = $this->resolveSources($value, $source, $node, $params);

        return $value;
    }

    protected function resolveSources(string $value, object $source, object $node, array $params)
    {
        $sources = self::getSources($source);

        return preg_replace_callback(self::SOURCE_REGEX, function (array $matches) use ($sources, $node, $params) {
            $id = $matches[1];

            if (!isset($sources[$id])) {
                return '';
            }

            $src = (object) ($sources[$id] ?? []);

            return $this->resolveSource($src, $node, $params);
        }, $value);
    }

    protected function resolveSource(object $src, object $node, array $params)
    {
        static $resolvedSources = [];

        $cacheKey = md5(json_encode([$node, $src, $params['data'] ?? []]));

        if (!isset($resolvedSources[$cacheKey])) {
            $v = $this->manager->resolve($src, $node, $params);

            if (is_string($v)) {
                $resolvedSources[$cacheKey] = $v;
            } else {
                Event::emit('yooessentials.info', [
                    'addon' => 'dynamic',
                    'resolver' => 'composed',
                    'error' => 'Composed source did not resolve to a string, but: ' . gettype($v),
                    'source' => $src,
                ]);
            }
        }

        return $resolvedSources[$cacheKey] ?? '';
    }

    protected function resolveConditions(string $value, object $source, object $node, array $params)
    {
        $conditions = self::getConditions($source);

        return preg_replace_callback(self::CONDITION_REGEX, function (array $matches) use ($conditions, $node, $params) {
            $id = $matches[1];

            if (!isset($conditions[$id])) {
                return '';
            }

            $condition = (object) ($conditions[$id] ?? []);

            $condition->id = $id;
            $result = $this->resolveCondition($condition, $node, $params);
            $content = explode('{{ else }}', $matches[2]);

            // add placeholders when in customizer
            if ($this->config->get('app.isCustomizer')) {
                if (isset($content[0]) && trim($content[0]) === '') {
                    $content[0] = '{{ Condition Met }}';
                }

                if (isset($content[1]) && trim($content[1]) === '') {
                    $content[1] = '{{ Condition Not Met }}';
                }
            }

            return $result ? $content[0] : ($content[1] ?? '');
        }, $value);
    }

    protected function resolveCondition(object $condition, object $node, array $params)
    {
        $query = $condition->rules_eval ?? null;
        $rules = array_map(fn ($rule) => (object) $rule, $condition->rules ?? []);

        if (!$rules) {
            return false;
        }

        if ($query === ConditionManager::MODE_CUSTOM) {
            $query = $condition->rules_eval_custom ?? null;
        }

        if (!$query) {
            $query = ConditionManager::MODE_AND;
        }

        /** @var ConditionResolver $resolver */
        $resolver = (new ConditionResolver($rules))->withQuery($query);

        if (app()->config->get('app.isCustomizer')) {
            $nodeId = $node->attrs['data-id'] ?? $params['parent']->attrs['data-id'] ?? null;
            $field = Arr::get($params, 'yooessentials.field');

            $id = "$nodeId#$field#$condition->id";

            $resolver->withLogger(Logger::createLogger($id, 'composed'));
        }

        return $resolver->resolve($node, $params);
    }

    protected static function getSources(object $source): array
    {
        return array_map(fn ($src) => (object) $src, (array) ($source->composed->sources ?? []));
    }

    protected static function getConditions(object $source): array
    {
        return array_map(fn ($src) => (object) $src, (array) ($source->composed->conditions ?? []));
    }

    protected static function isComposed(object $source): bool
    {
        return isset($source->composed);
    }
}
