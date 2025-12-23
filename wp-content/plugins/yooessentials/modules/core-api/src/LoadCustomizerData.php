<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api;

use YOOtheme\Config;

class LoadCustomizerData
{
    public Config $config;
    public MaxMind\GeoIp $geoip;

    public function __construct(Config $config, MaxMind\GeoIp $geoip)
    {
        $this->geoip = $geoip;
        $this->config = $config;
    }

    public function handle(): void
    {
        $this->config->add('yooessentials.customizer', [
            'geoipcity' => $this->geoip->isCityDb(),
            'geoipcountry' => $this->geoip->isCountryDb(),
        ]);
    }
}
