<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Listener;

use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Url;
use YOOtheme\File;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;

    public function __construct(Config $config, Metadata $metadata)
    {
        $this->config = $config;
        $this->metadata = $metadata;
    }

    public function loadScript(): void
    {
        $this->config->add('yooessentials.customizer', [
            'base' => ($base = Url::to('~yooessentials_url')),
            'assets' => Url::to("{$base}/modules"),
        ]);

        $values = [
            'version' => $this->config->get('yooessentials.version'),
            'customizer' => $this->config->get('yooessentials.customizer')
        ];

        $this->metadata->set(
            'script:yooessentials',
            sprintf(
                'window.yooessentials ||= {}; yooessentials = %s;',
                json_encode($values),
            ),
        );
    }

    public function loadAssets(): void
    {
        $this->config->addFile('yooessentials.core.fields', Path::get('../../config/fields.json'));

        // hint that an addon is present, hence not free version
        $this->config->set('yooessentials.addons.access', File::exists('~yooessentials/modules/access/bootstrap.php'));
        $this->config->set('yooessentials.addons.source', File::exists('~yooessentials/modules/source/bootstrap.php'));
        $this->config->set('yooessentials.addons.form', File::exists('~yooessentials/modules/form/bootstrap.php'));
        $this->config->set('yooessentials.addons.icon', File::exists('~yooessentials/modules/icon/bootstrap.php'));
        $this->config->set('yooessentials.addons.dynamic', File::exists('~yooessentials/modules/dynamic/bootstrap.php'));
        $this->config->set('yooessentials.addons.layout', File::exists('~yooessentials/modules/layout/bootstrap.php'));
        $this->config->set('yooessentials.addons.element', File::exists('~yooessentials/modules/element/bootstrap.php'));

        $this->metadata->set('script:yooessentials-core', [
            'src' => '~yooessentials_url/modules/core/yooessentials.min.js',
            'defer' => true,
        ]);

        $this->metadata->set('style:yooessentials-core', [
            'href' => '~yooessentials_url/modules/core/yooessentials.min.css',
            'defer' => true,
        ]);
    }
}
