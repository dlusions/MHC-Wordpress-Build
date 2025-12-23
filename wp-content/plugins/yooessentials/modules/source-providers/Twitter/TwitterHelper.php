<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Twitter;

class TwitterHelper
{
    public static function parseText(string $text, array $entities): string
    {
        $replacements = [];

        // Prepare URL replacements
        foreach ($entities['urls'] ?? [] as $url) {
            $display = $url['display_url'];
            $expanded = $url['expanded_url'] ?? null;
            $link = self::renderUrl($expanded, $display);

            $start = $url['start'];
            $end = $url['end'];
            $length = $end - $start;

            if (mb_substr($text, $start, $length, 'UTF-8') === $url['url']) {
                $replacements[] = [
                    'start' => $start,
                    'length' => $length,
                    'replace' => $link
                ];
            }
        }

        // Prepare mention replacements
        foreach ($entities['mentions'] ?? [] as $mention) {
            $username = htmlspecialchars($mention['username']);
            $handle = "@$username";
            $link = self::renderUrl("https://twitter.com/$username", $handle);

            $start = $mention['start'];
            $end = $mention['end'];
            $length = $end - $start;

            if (mb_substr($text, $start, $length, 'UTF-8') === $handle) {
                $replacements[] = [
                    'start' => $start,
                    'length' => $length,
                    'replace' => $link
                ];
            }
        }

        // Prepare hashtag replacements
        foreach ($entities['hashtags'] ?? [] as $hashtag) {
            $tag = $hashtag['tag'];
            $hashtagText = "#$tag";
            $link = self::renderUrl("https://twitter.com/hashtag/$tag", $hashtagText);

            $start = $hashtag['start'];
            $end = $hashtag['end'];
            $length = $end - $start;

            if (mb_strtolower(mb_substr($text, $start, $length, 'UTF-8')) === mb_strtolower($hashtagText)) {
                $replacements[] = [
                    'start' => $start,
                    'length' => $length,
                    'replace' => $link
                ];
            }
        }

        // Sort replacements by start position descending
        usort($replacements, fn ($a, $b) => $b['start'] <=> $a['start']);

        // Apply all replacements at once (using mb_substr_replace)
        foreach ($replacements as $r) {
            $text = self::mb_substr_replace($text, $r['replace'], $r['start'], $r['length']);
        }

        $text = nl2br($text);

        return $text;
    }

    /**
     * Multibyte-safe substr_replace
     */
    protected static function mb_substr_replace($string, $replacement, $start, $length = null, $encoding = 'UTF-8')
    {
        $stringLength = mb_strlen($string, $encoding);
        if ($start < 0) {
            $start = $stringLength + $start;
            if ($start < 0) {
                $start = 0;
            }
        }
        if (is_null($length)) {
            $length = $stringLength - $start;
        } elseif ($length < 0) {
            $length = $stringLength - $start + $length;
        }

        return mb_substr($string, 0, $start, $encoding)
            . $replacement
            . mb_substr($string, $start + $length, null, $encoding);
    }

    protected static function renderUrl(string $href, ?string $text = null): string
    {
        $text ??= htmlspecialchars($href, ENT_QUOTES, 'UTF-8');

        return "<a href=\"$href\" target=\"_blank\" rel=\"noopener noreferrer\">$text</a>";
    }

    protected static function renderOrganizationUrl($name, $id): string
    {
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        return "<a href=\"https://www.linkedin.com/company/$id\" target=\"_blank\" rel=\"noopener noreferrer\">@$name</a>";
    }
}
