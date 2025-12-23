<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Listener;

use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;
    public AuthManager $manager;

    public function __construct(Config $config, Metadata $metadata, AuthManager $manager)
    {
        $this->config = $config;
        $this->metadata = $metadata;
        $this->manager = $manager;
    }

    public function handle(): void
    {
        $this->config->addFile('customizer', Path::get('../../config/customizer.json'));
        $this->config->set('yooessentials.customizer.auth_drivers', $this->manager->drivers());
    }
}
