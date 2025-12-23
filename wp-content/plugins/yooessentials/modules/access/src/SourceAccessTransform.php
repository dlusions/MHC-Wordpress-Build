<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access;

use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;

class SourceAccessTransform
{
    protected SourceResolverManager $sourceResolver;

    public function __construct(SourceResolverManager $sourceResolver)
    {
        $this->sourceResolver = $sourceResolver;
    }

    public function preload(object $node, array &$params)
    {
        if (($params['context'] ?? '') !== 'render') {
            return;
        }

        if (!isset($node->props['yooessentials_access_conditions'])) {
            return;
        }

        $conditions = (array) ($node->props['yooessentials_access_conditions'] ?? []);

        foreach ($conditions as $condition) {
            $this->sourceResolver->preloadAdjacentProps($condition, $node, $params);
        }
    }

    public function prerender(object $node, array &$params)
    {
        $conditions = (array) ($node->props['yooessentials_access_conditions'] ?? []);

        foreach ($conditions as $condition) {
            $condition->props = (array) ($condition->props ?? []);
            $this->sourceResolver->resolveAdjacentProps($condition, $node, $params);
        }
    }
}
