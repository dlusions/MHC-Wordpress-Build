<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Device;

use YOOtheme\Arr;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Condition\ConditionRule;
use ZOOlanders\YOOessentials\Condition\MobileDetect;
use ZOOlanders\YOOessentials\Util;

class OpSysRule extends ConditionRule
{
    private const OS = [
        'Mac OS X',
        'Mac OS Classic',
        'Linux',
        'Open BSD',
        'Sun OS',
        'QNX',
        'BeOS',
        'OS/2',
        'Windows',
        'Windows Vista',
        'Windows Server 2003',
        'Windows XP',
        'Windows 2000 sp1',
        'Windows 2000',
        'Windows NT',
        'Windows Me',
        'Windows 98',
        'Windows 95',
        'Windows CE',
    ];

    public function resolve($props, $node): bool
    {
        if (!isset($props->os) or !$this->getAgent()) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        $selection = $props->os;

        if (is_string($selection)) {
            $selection = Util\Arr::explodeList($selection);
        }

        return Arr::some($selection, fn ($s) => $this->_resolve($s));
    }

    protected function _resolve($s): bool
    {
        $agent = $this->getAgent();

        preg_match('#(\D*)([\d\.]+)?-?([\d\.]+)?#i', $s, $matches);
        @list($match, $name, $min, $max) = $matches;

        if (!preg_match("#^$name$#i", $agent->name, $match)) {
            return false;
        }

        if ($min && !Str::contains($min, '.')) {
            $min = "$min.0";
        }

        if ($max && !Str::contains($max, '.')) {
            $max = "$max.0";
        }

        if ($min && !$max) {
            return version_compare($min, $agent->version, '>=');
        }

        if ($min && version_compare($agent->version, $min, '<') or $max && version_compare($agent->version, $max, '>')) {
            return false;
        }

        return true;
    }

    public function fields(): array
    {
        return [
            'os' => [
                'label' => 'Selection',
                'type' => 'textarea',
                'source' => true,
                'attrs' => [
                    'rows' => 4,
                    'placeholder' => "Linux\nWindows XP\nMac OS X 10.2-10.15",
                ],
                'description' => 'The list of Operative Systems that the browser agent must match, with optional version range. Separate the entries with a comma and/or new line.',
            ],
            '_supported' => [
                'label' => 'Supported OS',
                'type' => 'yooessentials-info',
                'content' => implode(', ', self::OS) . '.',
            ],
            '_notice' => [
                'type' => 'yooessentials-info',
                'content' => 'Keep in mind that operating system detection is not always accurate, users can setup their browser to mimic other agents.',
            ],
        ];
    }

    public function logArgs(object $props): array
    {
        return [
            'Agent' => $this->getAgent(),
        ];
    }

    protected function getAgent(): ?object
    {
        static $agent = null;

        if (!$agent) {
            $detect = new MobileDetect();
            $ag = $detect->getUserAgent();

            // standardize names
            $ag = str_replace('_', '.', $ag);
            $ag = str_replace('Mac OS X ', 'Mac OS X/', $ag);
            $ag = str_replace('Mac_PowerPC', 'Mac OS Classic', $ag);
            $ag = str_replace('Macintosh', 'Mac OS Classic', $ag);

            $ag = str_replace('X11', 'Linux', $ag);
            $ag = str_replace('Open BSD', 'OpenBSD', $ag);
            $ag = str_replace('Sun OS', 'SunOS', $ag);

            $ag = str_replace('Windows nt 10.0', 'Windows 10', $ag);
            $ag = str_replace('Windows nt 6.2', 'Windows 8', $ag);
            $ag = str_replace('Windows nt 6.1', 'Windows 7', $ag);
            $ag = str_replace('Windows nt 6.0', 'Windows Vista', $ag);
            $ag = str_replace('Windows nt 5.2', 'Windows Server 2003', $ag);
            $ag = str_replace('Windows nt 5.1', 'Windows XP', $ag);
            $ag = str_replace('Windows nt 5.01', 'Windows 2000 sp1', $ag);
            $ag = str_replace('Windows nt 5.0', 'Windows 2000', $ag);
            $ag = str_replace('Windows nt 4.0', 'Windows NT', $ag);
            $ag = str_replace('Win 9x 4.9', 'Windows Me', $ag);
            $ag = str_replace('Windows 98', 'Windows 98', $ag);
            $ag = str_replace('Windows 95', 'Windows 95', $ag);
            $ag = str_replace('Windows ce', 'Windows CE', $ag);

            $agent = $this->parseAgent($ag, self::OS);
        }

        return $agent;
    }

    protected function parseAgent($agent, $names)
    {
        $names = implode('|', $names);

        if (preg_match("#($names)[\/ ](\d+\.\d+)#i", $agent, $match)) {
            return (object) ['name' => str_replace(' ', '', $match[1]), 'version' => $match[2]];
        }

        return null;
    }
}
