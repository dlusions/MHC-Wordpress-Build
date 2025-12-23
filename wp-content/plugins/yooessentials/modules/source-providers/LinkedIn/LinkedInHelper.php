<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn;

class LinkedInHelper
{
    // https://learn.microsoft.com/en-us/linkedin/marketing/community-management/shares/little-text-format
    public static function parseCommentary(string $text): string
    {
        // Remove backslashes before punctuation (common in escaped LinkedIn posts)
        $text = preg_replace('/\\\\([:;#()!?,.\/\\\\])/', '$1', $text);

        // Convert URLs to clickable links
        $text = preg_replace_callback(
            '/(https?:\/\/[^\s<>"\'()]+(?:\([\w\d]+\)|[^[:punct:]\s]|\/)?)/i',
            fn ($matches) => self::renderUrl($matches[1]),
            $text
        );

        // Convert hashtags
        $text = preg_replace_callback(
            '/(#|＃)(\w{2,100})/u',
            fn ($matches) => static::renderHashtag($matches[2]),
            $text
        );

        // Convert template hashtags
        $text = preg_replace_callback(
            '/\{hashtag\|(\#|\＃)\|([^\}]{2,100})\}/u',
            fn ($matches) => static::renderHashtag($matches[2]),
            $text
        );

        // Replace organization @mentions in the format @[name](urn:li:organization:1234)
        $text = preg_replace_callback(
            '/@\[([^\]]+)\]\(urn:li:organization:([^)]+)\)/',
            fn ($matches) => static::renderUrl("https://www.linkedin.com/company/$matches[2]", "@$matches[1]"),
            $text
        );

        $text = preg_replace_callback(
            '/@\[([^\]]+)\]\(urn:li:person:([^)]+)\)/',
            fn ($matches) => "@$matches[1]",
            $text
        );

        // Replace line breaks with <br>
        return nl2br($text);
    }

    protected static function renderUrl(string $href, ?string $text = null): string
    {
        $text ??= htmlspecialchars($href, ENT_QUOTES, 'UTF-8');

        return "<a href=\"$href\" target=\"_blank\" rel=\"noopener noreferrer\">$text</a>";
    }

    protected static function renderHashtag($hashtag): string
    {
        $url = 'https://www.linkedin.com/search/results/all/?keywords=' . rawurlencode('#' . $hashtag);

        return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer"><span><span aria-hidden="true">#</span>' . htmlspecialchars($hashtag, ENT_QUOTES, 'UTF-8') . '</span></a>';
    }
}
