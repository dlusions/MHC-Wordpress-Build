<?php /**
 * @package     [FS] Switcher Pro for YOOtheme Pro
 * @subpackage  fs-switcher
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/switcher
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection DuplicatedCode */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $props */
/** @var $child */
/** @var $thumbnail_image */
/** @var $thumbnail_icon */

// Thumbnail
if ($thumbnail_image) {
    // Helpers
    $thumbnail_hover_image = $child->props['thumbnail_hover'];
    $has_thumbnail_hover = $props['switcher_thumbnail_hover'] && $thumbnail_hover_image;
    $thumbnail_alt = $child->props['title'] ?: $child->props['label'] ?: '';
    $thumbnail_alt = htmlspecialchars(trim(strip_tags($thumbnail_alt)), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $has_svg_color = $props['switcher_thumbnail_svg_inline'] && $props['switcher_thumbnail_svg_color'] && $this->isImage($thumbnail_image) === 'svg';

    // Overrides
    $props['switcher_thumbnail_text_color'] = $child->props['switcher_thumbnail_text_color'] ?: $props['switcher_thumbnail_text_color'];

    // Thumbnav Image
    $thumbnail = $this->el('image', [
        'class' => [
            'fs-switcher__nav-item-image',
            'uk-border-{switcher_thumbnail_border}',
            'uk-transition-opaque' => $has_thumbnail_hover,
            'uk-text-{switcher_thumbnail_svg_color}' => $has_svg_color,
            'uk-preserve-width {@switcher_nowrap} {@!switcher_thumbnav_shrink}',
            'uk-inverse-{switcher_thumbnail_text_color}',
        ],
        'src' => $thumbnail_image,
        'alt' => $thumbnail_alt,
        'width' => $props['switcher_thumbnail_width'] ?? 100,
        'height' => $props['switcher_thumbnail_height'],
        'focal_point' => $child->props['switcher_thumbnail_focal_point'],
        'loading' => $props['switcher_thumbnail_loading'] ? false : null,
        'fetchpriority' => $props['switcher_thumbnail_fetchpriority'] ? 'high' : null,
        'decoding' => $props['switcher_thumbnail_decoding'] ? 'async' : null,
        'thumbnail' => !$props['switcher_thumbnail_cache_disable'],
        'uk-svg' => $props['switcher_thumbnail_svg_inline'],
    ]);
    if ($has_thumbnail_hover) {
        // Thumbnav Image Hover
        $thumbnail_hover = $thumbnail_hover_image ? $this->el('image', [
            'class' => [
                'fs-switcher__nav-item-image--hover',
                'uk-border-{switcher_thumbnail_border}',
                'uk-transition-fade',
                'uk-preserve-width {@switcher_nowrap} {@!switcher_thumbnav_shrink}',
                'uk-inverse-{switcher_thumbnail_text_color}',
            ],
            'src' => $thumbnail_hover_image,
            'alt' => $thumbnail_alt,
            'width' => $props['switcher_thumbnail_width'] ?? 100,
            'height' => $props['switcher_thumbnail_height'],
            'focal_point' => $child->props['switcher_thumbnail_focal_point'],
            'loading' => $props['switcher_thumbnail_loading'] ? false : null,
            'fetchpriority' => $props['switcher_thumbnail_fetchpriority'] ? 'high' : null,
            'decoding' => $props['switcher_thumbnail_decoding'] ? 'async' : null,
            'thumbnail' => !$props['switcher_thumbnail_cache_disable'],
            'uk-svg' => $props['switcher_thumbnail_svg_inline'],
            'uk-cover' => true,
        ]) : null;
    }
} elseif ($thumbnail_icon) {
    // Thumbnav Icon
    $icon = $this->el('span', [
        'class' => ['fs-switcher__nav-item-icon', 'uk-text-{switcher_thumbnail_icon_color}'],
        'uk-icon' => [
            'icon: {0};' => $thumbnail_icon,
            "width: {0};" => $props['switcher_thumbnail_icon_width'] ?: ($props['switcher_thumbnail_width'] ?? 100),
        ],
    ]);
}