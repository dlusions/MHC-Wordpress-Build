<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic;

interface SourceResolverInterface
{
    public function setManager(SourceResolverManager $manager): self;

    public function preload(object $source, object $node, array $params);

    public function prerender(object $source, object $node, array $params);

    public function resolve(object $source, object $node, array $params);
}
