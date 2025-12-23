<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress\Listener;

use ZOOlanders\YOOessentials\Config\Config;
use YOOtheme\File;

class LoadGeoipDb
{
    public Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function handle(): void
    {
        // set geoip db path
        if (empty($this->config->get('core.geoipdb')) && ($geoipdb = \get_option('geoip-detect-manual_file'))) {
            $geoipdb = "~/$geoipdb";

            if (File::exists($geoipdb)) {
                $this->config->set('core.geoipdb', $geoipdb);
                $this->config->set('updated', true);
            }
        }
    }
}
