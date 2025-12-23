<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Listener;

use function YOOtheme\app;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use ZOOlanders\YOOessentials\Source\SourceService;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;
    public SourceService $sourceService;

    public function __construct(Config $config, Metadata $metadata, SourceService $sourceService)
    {
        $this->config = $config;
        $this->metadata = $metadata;
        $this->sourceService = $sourceService;
    }

    public function handle(): void
    {
        $this->config->addFile('customizer', Path::get('../../config/customizer.json'));
        $this->config->addFile('yooessentials.source.fields', Path::get('../../config/fields.json'));

        $providers = [];

        foreach ($this->sourceService->sourceProviders() as $type => $class) {
            $provider = app($class)->metadata();
            $providers[$provider->name] = (array) $provider;
        }

        $this->config->set('yooessentials.customizer.source_providers', $providers);

        $this->metadata->set('script:yooessentials-source', [
            'src' => '~yooessentials_url/modules/source/yooessentials-source.min.js',
            'defer' => true,
        ]);
    }
}
