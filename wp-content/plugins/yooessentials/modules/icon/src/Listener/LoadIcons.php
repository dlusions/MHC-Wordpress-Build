<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon\Listener;

use YOOtheme\Config;
use ZOOlanders\YOOessentials\Icon\IconLoader;

class LoadIcons
{
    public Config $config;
    public IconLoader $iconLoader;

    public function __construct(Config $config, IconLoader $iconLoader)
    {
        $this->config = $config;
        $this->iconLoader = $iconLoader;
    }

    public function handle(): void
    {
        if (!$this->config->get('app.isSite')) {
            return;
        }

        $items = array_merge(
            $this->config->get('~theme.menu.items', []),
            $this->config->get('~theme.header.social_items', []),
            $this->config->get('~theme.mobile.header.social_items', [])
        );

        foreach ($items as $item) {
            if (!empty($item['icon'])) {
                $this->iconLoader->loadIcon($item['icon']);
            }
        }
    }
}
