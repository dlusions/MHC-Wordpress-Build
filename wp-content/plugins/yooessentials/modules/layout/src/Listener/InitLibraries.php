<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout\Listener;

use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Layout\LayoutManager;

class InitLibraries
{
    public Config $config;
    public LayoutManager $manager;

    public function __construct(Config $config, LayoutManager $manager)
    {
        $this->config = $config;
        $this->manager = $manager;
    }

    public function handle(): void
    {
        $libraries = $this->config->get(LayoutManager::LIBRARIES_CONFIG_KEY, []);

        $this->manager->setLibraries($libraries);
    }
}
