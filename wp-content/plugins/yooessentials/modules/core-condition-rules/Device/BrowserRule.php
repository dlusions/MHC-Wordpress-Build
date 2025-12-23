<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Device;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Condition\MobileDetect;
use ZOOlanders\YOOessentials\Util;

class BrowserRule extends OpSysRule
{
    private const DESKTOP = ['Chrome', 'Firefox', 'Opera', 'Safari', 'Edge', 'MSIE'];

    private const MOBILE = [
        'Android',
        'iPad',
        'iPhone',
        'iPod',
        'Blackberry',
        'IEMobile',
        'NetFront',
        'NokiaBrowser',
        'Opera Mini',
        'Opera Mobi',
        'UC Browser',
    ];

    public function resolve($props, $node): bool
    {
        if (!isset($props->browsers) or !$this->getAgent()) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        $selection = $props->browsers;

        if (is_string($selection)) {
            $selection = Util\Arr::explodeList($selection);
        }

        return Arr::some($selection, fn ($s) => $this->_resolve($s));
    }

    public function fields(): array
    {
        return [
            'browsers' => [
                'label' => 'Selection',
                'type' => 'textarea',
                'source' => true,
                'attrs' => [
                    'rows' => 4,
                    'placeholder' => "Chrome\nAndroid 4\nFirefox 60-70",
                ],
                'description' => 'The list of browsers that the browser agent must match, with optional version range. Separate the entries with a comma and/or new line.',
            ],
            '_desktop' => [
                'label' => 'Supported Browsers',
                'type' => 'yooessentials-info',
                'content' => implode(', ', self::DESKTOP) . '.',
            ],
            '_mobile' => [
                'type' => 'yooessentials-info',
                'content' => implode(', ', self::MOBILE) . '.',
            ],
            '_notice' => [
                'type' => 'yooessentials-info',
                'content' => 'Keep in mind that browser detection is not always accurate, users can setup their browser to mimic other agents.',
            ],
        ];
    }

    protected function getAgent(): ?object
    {
        static $agent = null;

        if (!$agent) {
            $detect = new MobileDetect();
            $ag = $detect->getUserAgent();

            if ($detect->isMobile()) {
                return $agent = $this->parseAgent($ag, self::MOBILE);
            }

            switch (true) {
                case stripos($ag, 'Trident') !== false:
                    // Add MSIE to IE11 and others missing it
                    $ag = preg_replace('#(Trident\/[0-9\.]+;.*rv[: ]([0-9\.]+))#is', '\1 MSIE \2', $ag);

                    break;

                case stripos($ag, 'Chrome') !== false:
                    // Remove Safari from Chrome
                    $ag = preg_replace('#(Chrome\/.*)Safari/[0-9\.]*#is', '\1', $ag);
                    // Add MSIE to IE Edge and remove Chrome from IE Edge
                    $ag = preg_replace('#Chrome\/.*(Edge/[0-9])#is', 'MSIE \1', $ag);

                    break;

                case stripos($ag, 'Opera') !== false:
                    $ag = preg_replace('#(Opera\/.*)Version\/#is', '\1Opera/', $ag);

                    break;

                case stripos($ag, 'Safari') !== false:
                    $ag = preg_replace('#Version\/#is', 'Safari/', $ag);

                    break;
            }

            $agent = $this->parseAgent($ag, self::DESKTOP);
        }

        return $agent;
    }
}
