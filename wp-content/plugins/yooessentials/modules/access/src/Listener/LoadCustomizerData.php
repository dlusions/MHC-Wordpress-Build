<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access\Listener;

use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;

    public function __construct(Config $config, Metadata $metadata)
    {
        $this->config = $config;
        $this->metadata = $metadata;
    }

    public function handle(): void
    {
        $this->config->addFile('customizer', Path::get('../../config/customizer.json'));

        $this->metadata->set('script:yooessentials-access', [
            'src' => '~yooessentials_url/modules/access/yooessentials-access.min.js',
            'defer' => true,
        ]);
    }
}
