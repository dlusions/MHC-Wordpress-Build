<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Element\Markdown\Renderer;

use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Node\Node;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\CommonMark\Renderer\Block\ListBlockRenderer as CoreListBlockRenderer;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Renderer\ChildNodeRendererInterface;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Renderer\NodeRendererInterface;

final class ListBlockRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!$node instanceof ListBlock) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($node));
        }

        $listData = $node->getListData();

        $class = ['uk-list'];

        if ($listData->type === ListBlock::TYPE_BULLET && $listData->bulletChar === '-') {
            $class[] = 'uk-list-hyphen';
        }

        if ($listData->type === ListBlock::TYPE_BULLET && $listData->bulletChar === '*') {
            $class[] = 'uk-list-disc';
        }

        if ($listData->type === ListBlock::TYPE_BULLET && $listData->bulletChar === '+') {
            $class[] = 'uk-list-square';
        }

        if ($listData->type === ListBlock::TYPE_ORDERED) {
            $class[] = 'uk-list-decimal';
        }

        $node->data['attributes'] = ['class' => implode(' ', $class)];

        return (new CoreListBlockRenderer())->render($node, $childRenderer, $inTightList);
    }
}
