<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Debug\Listener;

use YOOtheme\Path;
use YOOtheme\Config;
use YOOtheme\Metadata;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;

    public function __construct(Metadata $metadata, Config $config)
    {
        $this->config = $config;
        $this->metadata = $metadata;
    }

    public function handle(): void
    {
        $this->config->addFile('customizer', Path::get('../../config/customizer.json'));

        $this->metadata->set('script:yooessentials-debug', [
            'src' => '~yooessentials_url/modules/core-debug/yooessentials-debug.min.js',
            'defer' => true,
        ]);
    }
}
