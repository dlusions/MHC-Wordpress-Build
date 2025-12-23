<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Resolvers;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverAbstract;

class SourceQueryConditionsResolver extends SourceResolverAbstract
{
    private string $queryMatch;

    private array $args;

    public function __construct(string $queryMatch, array $args = ['filters', 'ordering'])
    {
        $this->args = $args;
        $this->queryMatch = $queryMatch;
    }

    public function preload(object $source, object $node, array $params)
    {
        if (!$this->isConditioned($source)) {
            return;
        }

        if (!isset($node->essentials)) {
            $node->essentials = new \stdClass();
        }

        if (isset($node->source->query)) {
            $node->essentials->source_query = unserialize(serialize($node->source->query));
        }

        foreach ($this->args as $arg) {
            foreach ((array) ($source->query->arguments->{$arg} ?? []) as &$condition) {
                $this->manager->preloadAdjacentProps((object) $condition, $node, $params);
            }
        }
    }

    public function prerender(object $source, object $node, array $params)
    {
        if (!$this->isConditioned($source)) {
            return;
        }

        if (isset($node->essentials->source_query)) {
            $node->source->query = unserialize(serialize($node->essentials->source_query));
        }

        $this->resolve($node->source, $node, $params);
    }

    public function resolve(object $source, object $node, array $params)
    {
        if (!$this->isConditioned($source)) {
            return;
        }

        foreach ($this->args as $arg) {
            foreach ((array) ($source->query->arguments->{$arg} ?? []) as &$condition) {
                $this->manager->resolveAdjacentProps((object) $condition, $node, $params);

                // remove source as is not part of the gql schema and will error
                unset($condition->source);
                unset($condition->source_extended);

                // expected as obj, not sure why
                $condition->props = (object) ($condition->props ?? []);
            }
        }
    }

    protected function isConditioned(object $source): bool
    {
        if (!isset($source->query->arguments)) {
            return false;
        }

        if (Arr::every($this->args, fn ($arg) => empty($source->query->arguments->{$arg} ?? []))) {
            return false;
        }

        return preg_match($this->queryMatch, $source->query->name ?? '');
    }
}
