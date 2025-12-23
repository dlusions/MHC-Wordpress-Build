<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky;

/**
 * BlueskyFacetsProcessor
 *
 * A utility class to process Bluesky post facets and integrate them into post text.
 * Handles links, mentions, and tags according to the Bluesky API format.
 */
class BlueskyFacetsProcessor
{
    /**
     * Process text with facets and return HTML with integrated facets
     *
     * @param string $text The original post text
     * @param array $facets Array of facets from Bluesky API
     * @return string The processed text with facets integrated as HTML
     */
    public static function process(string $text, array $facets = []): string
    {
        if (empty($facets)) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }

        // Sort facets by start position to process them in order
        usort($facets, fn ($a, $b) => $a['index']['byteStart'] <=> $b['index']['byteStart']);

        // We'll build the result by replacing parts of the text
        $textBytes = $text;
        $segments = [];
        $lastEnd = 0;

        foreach ($facets as $facet) {
            $start = $facet['index']['byteStart'];
            $end = $facet['index']['byteEnd'];

            // Skip overlapping facets
            if ($start < $lastEnd) {
                continue;
            }

            // Get the text before this facet
            if ($start > $lastEnd) {
                $segments[] = htmlspecialchars(mb_strcut($textBytes, $lastEnd, $start - $lastEnd, 'UTF-8'), ENT_QUOTES, 'UTF-8');
            }

            // Get the facet text
            $facetText = htmlspecialchars(mb_strcut($textBytes, $start, $end - $start, 'UTF-8'), ENT_QUOTES, 'UTF-8');

            // Process the facet based on its features
            $segments[] = self::processFacetFeatures($facetText, $facet['features']);

            // Update last end position
            $lastEnd = $end;
        }

        // Add remaining text
        if ($lastEnd < mb_strlen($textBytes, '8bit')) {
            $segments[] = htmlspecialchars(mb_strcut($textBytes, $lastEnd, null, 'UTF-8'), ENT_QUOTES, 'UTF-8');
        }

        return implode('', $segments);
    }

    /**
     * Process facet features and return HTML
     *
     * @param string $text The facet text
     * @param array $features Array of facet features
     * @return string HTML representation of the facet
     */
    private static function processFacetFeatures(string $text, array $features): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        foreach ($features as $feature) {
            $type = $feature['$type'] ?? '';

            switch ($type) {
                case 'app.bsky.richtext.facet#link':
                    $uri = htmlspecialchars($feature['uri'] ?? '', ENT_QUOTES, 'UTF-8');

                    return "<a href=\"{$uri}\" target=\"_blank\" rel=\"noopener noreferrer\">{$text}</a>";

                case 'app.bsky.richtext.facet#mention':
                    $did = htmlspecialchars($feature['did'] ?? '', ENT_QUOTES, 'UTF-8');
                    $profileUrl = "https://bsky.app/profile/{$did}";

                    return "<a href=\"{$profileUrl}\" target=\"_blank\" rel=\"noopener noreferrer\">{$text}</a>";

                case 'app.bsky.richtext.facet#tag':
                    $tag = htmlspecialchars($feature['tag'] ?? '', ENT_QUOTES, 'UTF-8');
                    $tagUrl = "https://bsky.app/hashtag/{$tag}";

                    return "<a href=\"{$tagUrl}\" target=\"_blank\" rel=\"noopener noreferrer\">{$text}</a>";
            }
        }

        return $text;
    }
}
