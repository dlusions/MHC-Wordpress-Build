<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Url;

use function YOOtheme\app;
use YOOtheme\Arr;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Condition\ConditionRule;

class UrlRule extends ConditionRule
{
    public function resolveProps(object $props, object $node): object
    {
        if (!isset($props->urls)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        $regex = $props->regex ?? false;

        return (object) [
            'regex' => $regex,
            'urls' => self::parseUrls($props->urls, $regex),
            'requestUrl' => app()->config->get('req.href') ?? '',
        ];
    }

    public function resolve($props, $node): bool
    {
        $requestUrls = self::parseRequestUrl($props->requestUrl);

        return Arr::some($requestUrls, fn ($url) => Arr::some($props->urls, function ($pattern) use ($url, $props) {
            if (!$props->regex) {
                return Str::contains($url, $pattern);
            }

            if (@preg_match("{$pattern}u", $url) || @preg_match($pattern, $url)) {
                return true;
            }

            return false;
        }));
    }

    protected static function parseUrls(string $urls, bool $regex): array
    {
        $urls = self::parseTextareaList($urls);

        return array_map(function ($url) use ($regex) {
            if ($regex) {
                $url = str_replace(['#', '&amp;'], ['\#', '(&amp;|&)'], $url);
                $url = "#{$url}#si";
            }

            return $url;
        }, $urls);
    }

    /**
     * Code adapted from Regular Labs Library version 20.9.11663
     *
     * @author Peter van Westen
     * @copyright Copyright © 2020 Regular Labs All Rights Reserved
     * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
     */
    protected static function parseRequestUrl(string $url): array
    {
        static $urls = [];

        if (!empty($urls)) {
            return $urls;
        }

        $urls = [
            html_entity_decode(urldecode($url), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            urldecode($url),
            html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            $url,
        ];

        return $urls = array_unique($urls);
    }
}
