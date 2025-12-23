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

class AdjacentSourceResolver extends SourceResolverAbstract
{
    public function preload(object $source, object $node, array $params)
    {
        if (self::isAdjacent($source, $node) && isset($params['source']->source)) {
            $this->addChildQuery($params['source'], $source, false);
        }
    }

    public function resolve(object $source, object $node, array $params)
    {
        if (!self::isAdjacent($source, $node)) {
            return null;
        }

        $data = $params['data'] ?? null;

        return Arr::get($data, $source->name);
    }

    protected static function isAdjacent(object $source, object $node): bool
    {
        return isset($source->name) && !isset($source->composed) && !isset($source->query) && isset($node->source);
    }
}
