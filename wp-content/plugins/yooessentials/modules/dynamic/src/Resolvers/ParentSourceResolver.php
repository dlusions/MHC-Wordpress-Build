<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Resolvers;

use YOOtheme\Arr;
use YOOtheme\Builder\Source\SourceQuery;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverAbstract;

class ParentSourceResolver extends SourceResolverAbstract
{
    public function preload(object $source, object $node, array $params)
    {
        if (self::isInheriting($source) && $closest = self::findClosestInheritableNode($node, $params)) {
            $this->addChildQuery($closest, $source);
        }
    }

    public function resolve(object $source, object $node, array $params)
    {
        if (self::isInheriting($source)) {
            $closest = self::findClosestInheritableNode($node, $params);
            $data = $closest->source_data ?? [];

            if (!empty($data)) {
                if (self::isExpanding($source)) {
                    return Arr::get($data, $source->query->field->name);
                }

                return $data;
            }

            // prevent further resolving
            return false;
        }
    }

    protected static function isInheriting(object $source): bool
    {
        return ($source->query->name ?? '') === SourceQuery::PARENT && isset($source->name);
    }

    protected static function isExpanding(object $source): bool
    {
        return self::isInheriting($source) && isset($source->query->field->name);
    }

    protected static function findClosestInheritableNode(object $node, array $params): ?object
    {
        foreach ($params['path'] ?? [] as $ancestor) {
            if ($ancestor !== $node && (isset($ancestor->source->query->name) || isset($ancestor->source->data))) {
                return $ancestor;
            }
        }

        return null;
    }
}
