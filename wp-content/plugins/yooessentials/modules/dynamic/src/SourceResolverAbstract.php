<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic;

abstract class SourceResolverAbstract implements SourceResolverInterface
{
    protected SourceResolverManager $manager;

    public function setManager(SourceResolverManager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function preload(object $source, object $node, array $params)
    {
    }

    public function prerender(object $source, object $node, array $params)
    {
    }

    public function resolve(object $source, object $node, array $params)
    {
    }

    public function addChildQuery(object $node, object $source, bool $inherit = true): void
    {
        if (!isset($source->name)) {
            return;
        }

        if ($inherit) {
            $node->inherited = true;
        }

        $child = (object) [
            'type' => '#essentials',
            'source' => (object) [
                'query' => (object) [
                    'name' => '#parent',
                    'field' => isset($source->query->field->name) ? $source->query->field : null,
                ],
                'props' => (object) [
                    'content' => $source,
                ],
            ],
        ];

        $node->source->children[] = $child;
    }
}
