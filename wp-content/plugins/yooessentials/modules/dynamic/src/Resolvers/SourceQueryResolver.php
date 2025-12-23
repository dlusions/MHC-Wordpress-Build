<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Resolvers;

use YOOtheme\Arr;
use YOOtheme\Event;
use YOOtheme\Builder\Source\SourceQuery;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverAbstract;

class SourceQueryResolver extends SourceResolverAbstract
{
    public function resolve(object $source, object $node, array $params)
    {
        if (empty($source->query->name)) {
            return null;
        }

        return $this->resolveQuery($source, $params);
    }

    protected function resolveQuery(object $source, array $params): ?array
    {
        if (!$result = $this->querySource($source, $params)) {
            return null;
        }

        $name = 'data';

        // add query name
        if ($source->query->name !== SourceQuery::PARENT) {
            $name .= ".{$source->query->name}";
        }

        // add field name
        if (isset($source->query->field)) {
            $name .= ".{$source->query->field->name}";
        }

        return (array) Arr::get($result, $name);
    }

    protected function querySource(object $source, array $params): ?array
    {
        // mock node to resolve prop query
        $node = new \stdClass();
        $node->type = 'text';
        $node->source = (object) [
            'props' => [
                'content' => $source,
            ],
            'query' => (object) $source->query,
        ];

        // allows injecting query results
        if ($result = Event::emit('yooessentials.source.query', $node, $params)) {
            return $result;
        }

        return $this->manager->yooTransform->querySource($node, $params);
    }
}
