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

class NodeSourceResolver extends SourceResolverAbstract
{
    public const NODE = '#node';

    public function preload(object $source, object $node, array $params)
    {
        if (self::isNodeInheriting($source, $node) && isset($params['source']->source)) {
            $this->addChildQuery($params['source'], $source, false);
        }
    }

    public function resolve(object $source, object $node, array $params)
    {
        if (self::isExpanding($source)) {
            return Arr::get($params['data'] ?? [], $source->query->field->name);
        }

        if (self::isNodeInheriting($source, $node)) {
            return $params['data'] ?? [];
        }
    }

    protected static function isNodeInheriting(object $source, object $node): bool
    {
        if (($source->query->name ?? self::NODE) === self::NODE && isset($source->name)) {
            return true;
        }

        return !isset($source->query->name) && isset($source->name) && self::hasQuery($node);
    }

    protected static function isExpanding(object $source): bool
    {
        return ($source->query->name ?? self::NODE) === self::NODE && isset($source->query->field->name);
    }

    protected static function hasQuery(object $node): bool
    {
        return isset($node->source->data) || isset($node->source->query->name);
    }
}
