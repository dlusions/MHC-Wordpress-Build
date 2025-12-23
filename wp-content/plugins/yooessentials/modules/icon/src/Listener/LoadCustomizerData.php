<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon\Listener;

use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use ZOOlanders\YOOessentials\Icon\IconLoader;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;
    public IconLoader $icons;

    public function __construct(Config $config, Metadata $metadata, IconLoader $icons)
    {
        $this->icons = $icons;
        $this->config = $config;
        $this->metadata = $metadata;
    }

    public function handle(): void
    {
        $this->config->add('yooessentials.customizer.icon_collections', $this->icons->getCollections());
        $this->config->addFile('customizer', Path::get('../../config/customizer.json'));

        $this->metadata->set('script:yooessentials-icon', [
            'src' => '~yooessentials_url/modules/icon/yooessentials-icon.min.js',
            'defer' => true,
        ]);
    }
}
