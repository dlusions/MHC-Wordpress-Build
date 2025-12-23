<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon;

class IconTransform
{
    protected IconLoader $loader;

    public function __construct(IconLoader $loader)
    {
        $this->loader = $loader;
    }

    public function __invoke(object $node, array $params): void
    {
        foreach ($this->loader->retrieveIcons($node, $params['type']) as $icon) {
            $this->loader->loadIcon($icon);
        }
    }
}
