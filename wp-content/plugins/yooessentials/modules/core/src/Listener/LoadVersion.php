<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Listener;

use YOOtheme\Config;
use YOOtheme\Str;

class LoadVersion
{
    public Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function handle($meta)
    {
        if (Str::contains($meta->name, ':yooessentials')) {
            $version = $this->config->get('yooessentials.version');
            $build = $this->config->get('yooessentials.build');
            $meta = $meta->withAttribute('version', $version . ($build ? "-$build" : ''));
        }

        return $meta;
    }
}
