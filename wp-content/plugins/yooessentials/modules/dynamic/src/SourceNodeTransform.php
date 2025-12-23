<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic;

class SourceNodeTransform
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

        if (isset($node->source)) {
            $this->resolver->preload((object) $node->source, $node, $params);
        }
    }

    public function prerender(object $node, array &$params)
    {
        if (isset($node->source)) {
            $this->resolver->prerender((object) $node->source, $node, $params);
        }
    }
}
