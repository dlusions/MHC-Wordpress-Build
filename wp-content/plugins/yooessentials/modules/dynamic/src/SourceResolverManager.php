<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic;

use YOOtheme\Arr;
use YOOtheme\Builder\Source;
use YOOtheme\Builder\Source\SourceTransform as YooSourceTransform;

class SourceResolverManager
{
    protected array $resolvers = [];

    public YooSourceTransform $yooTransform;

    public function __construct(YooSourceTransform $yooTransform)
    {
        $this->yooTransform = $yooTransform;
    }

    public function addResolver(SourceResolverInterface $resolver, ?int $offset = null): self
    {
        $resolver->setManager($this);

        Arr::splice($this->resolvers, $offset, 0, $resolver);

        return $this;
    }

    public function preload(object $source, object $node, array $params)
    {
        foreach ($this->resolvers as $resolver) {
            $resolver->preload($source, $node, $params);
        }
    }

    public function prerender(object $source, object $node, array $params): void
    {
        foreach ($this->resolvers as $resolver) {
            $resolver->prerender($source, $node, $params);
        }
    }

    public function resolveProps(object $node, array $params)
    {
        $props = (array) ($node->source_extended->props ?? []);

        foreach ($props as $name => $source) {
            Arr::set($params, 'yooessentials.field', $name);

            $value = $this->resolve((object) $source, $node, $params);

            $node->props[$name] = $value;
        }
    }

    public function resolve(object $source, object $node, array $params, bool $filters = true)
    {
        $data = null;

        foreach ($this->resolvers as $resolver) {
            $data = $resolver->resolve($source, $node, $params);

            if (is_string($data) || $data === false) {
                break;
            }

            if (is_array($data)) {
                $data = array_is_list($data)
                    ? array_map(fn ($v) => Arr::get($v, $source->name), $data)
                    : Arr::get($data, $source->name);

                if ($filters) {
                    $data = $this->applyFilters($data, $source, $params);
                }

                break;
            }
        }

        return $data;
    }

    public function preloadAdjacentProps(object $adjacent, object $node, array $params)
    {
        $props = array_merge(
            (array) ($adjacent->source->props ?? []),
            (array) ($adjacent->source_extended->props ?? [])
        );

        foreach ($props as $source) {
            $this->preload((object) $source, $node, $params);
        }
    }

    public function resolveAdjacentProps(object $adjacent, object $node, array $params)
    {
        $props = array_merge(
            (array) ($adjacent->source->props ?? []),
            (array) ($adjacent->source_extended->props ?? [])
        );

        $adjacent->props = (array) ($adjacent->props ?? []);

        foreach ($props as $name => $source) {
            $value = $this->resolve((object) $source, $node, $params);
            $adjacent->props[$name] = $value;
        }
    }

    /**
     * @param array|string $value
     * @return array|string|null
     */
    public function applyFilters($value, object $source, array $params)
    {
        $isMulti = is_array($value);
        $implode = $source->implode->join ?? null;
        $separator = $source->implode->glue ?? '';

        if ($isMulti && $implode === 'before') {
            $value = implode($separator, $value);
            $isMulti = false;
        }

        // apply filters
        $filters = isset($source->filters) ? (array) $source->filters : [];

        if ($isMulti) {
            foreach ($value as $i => $val) {
                $value[$i] = $this->_applyFilters(self::toString($val), $filters, $params);
            }
        } else {
            $value = $this->_applyFilters(self::toString($value), $filters, $params);
        }

        if ($isMulti && $implode === 'after') {
            $value = implode($separator, $value);
        }

        return $value;
    }

    protected function _applyFilters(string $value, array $filters, array $params): ?string
    {
        foreach (array_intersect_key($this->yooTransform->filters, $filters) as $key => $filter) {
            $value = $filter($value, $filters[$key], $filters, $params);
        }

        return $value;
    }

    protected static function toString($str)
    {
        if (is_scalar($str) || is_callable([$str, '__toString'])) {
            return trim((string) $str);
        }

        return '';
    }

    public static function getSourceTypeDefinitions(Source $source): array
    {
        $reflection = new \ReflectionClass($source);
        $property = $reflection->getProperty('types');
        $property->setAccessible(true);

        return $property->getValue($source);
    }
}
