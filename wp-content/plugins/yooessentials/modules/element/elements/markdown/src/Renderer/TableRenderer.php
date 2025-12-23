<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Element\Markdown\Renderer;

use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\Table\TableRenderer as CoreTableRenderer;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Node\Node;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Renderer\ChildNodeRendererInterface;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Renderer\NodeRendererInterface;

final class TableRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        $node->data['attributes'] = ['class' => 'uk-table'];

        return (new CoreTableRenderer())->render($node, $childRenderer);
    }
}
