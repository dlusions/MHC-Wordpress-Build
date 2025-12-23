<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic;

class SourcePropsTransform
{
    protected SourceResolverManager $resolver;

    public function __construct(SourceResolverManager $resolver)
    {
        $this->resolver = $resolver;
    }

    public function preload(object $node, array $params)
    {
        if (($params['context'] ?? '') !== 'render') {
            return;
        }

        if (isset($node->source_extended)) {
            $props = (array) ($node->source_extended->props ?? []);

            foreach ($props as $source) {
                $this->resolver->preload((object) $source, $node, $params);
            }
        }
    }

    public function prerender(object $node, array &$params)
    {
        if (isset($node->inherited)) {
            $node->source_data = $node->source->data ?? $params['data'] ?? [];
        }

        // in case a node is cloned, source_extended must be deeply cloned
        if (isset($node->source_extended)) {
            $node->source_extended = unserialize(serialize($node->source_extended));
        }

        $this->resolver->resolveProps($node, $params);
    }
}
