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

/** @noinspection NestedTernaryOperatorInspection, DuplicatedCode */

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

use FlartStudio\YOOtheme\Switcher\LightboxHelper;

// Load Helper
require_once Path::get('./src/LightboxHelper.php', __DIR__);

// Ensure these variables exist
/** @var $element */
/** @var $props */

// Text attributes (sanitize)
foreach (['link_aria_label', 'link_title'] as $key) {
    $element[$key] = htmlspecialchars(strtolower(trim(strip_tags((string)($props[$key] ?: $element[$key] ?: '')))),
        ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Boolean attributes (simple fallback)
foreach (
    [
        'link_target',
        'link_download',
        'link_rel_nofollow',
        'link_rel_noreferrer',
        'link_rel_noopener',
        'link_rel_prefetch',
    ] as $key
) {
    $element[$key] = $props[$key] ?: $element[$key] ?: false;
}

// Standard link
$link = !empty($props['link']) ? $this->el('a', [
    'href' => $props['link'],
]) : null;

// Lightbox Image (Dual Link Mode)
$link_lightbox = ($element['lightbox'] && $props['image'] && $props['link_toggle'])
    ? $this->el('a', LightboxHelper::getAttributes($this, $element, $props, $props['image_src']))
    : null;

// Lightbox and link processing
if ($link && $element['lightbox'] && !$props['link_toggle']) {
    $link->attr(LightboxHelper::getAttributes($this, $element, $props, $props['link']));
    if ($element['title_display'] === 'lightbox') {
        $props['title'] = '';
    }
    if ($element['content_display'] === 'lightbox') {
        $props['content'] = '';
    }
} elseif ($link) {
    $link->attr([
        'class' => [$props['link_class']],
        ...(empty($props['link_toggle']) ? [
            'target' => $this->expr(['_blank {@link_target}'], $element) ?: false,
            'download' => $element['link_download'],
            'rel' => $this->expr([
                'nofollow {@link_rel_nofollow}',
                'noreferrer {@link_rel_noreferrer}',
                'noopener {@link_rel_noopener}',
                'prefetch {@link_rel_prefetch}',
            ], $props) ?: false,
            'uk-scroll' => str_starts_with($props['link'], '#'),
        ] : []),
        'uk-toggle' => str_starts_with($props['link'], '#') && $props['link_toggle'],
        $this->expr(['{link_attributes}'], $props) ?: false,
        'aria-label' => $this->expr(['{link_aria_label}'], $element) ?: false,
        'title' => $this->expr(['{link_title}'], $element) ?: false,
    ]);
}

// Title link handling
if ($link && $props['title'] && $element['title_link']) {
    $props['title'] = $link($element, ['class' => ['uk-link-{title_hover_style}']], $this->striptags($props['title']));
}

// Image link handling
if ($link && $props['image'] && $element['image_link']) {
    $props['image'] = $link_lightbox
        ? $link_lightbox($element, ['class' => []], $props['image'])
        : $link($element, ['class' => []], $props['image']);
    $props['title'] = ($props['title'] && $element['title_display'] === 'lightbox') ? '' : $props['title'];
    $props['content'] = ($props['content'] && $element['content_display'] === 'lightbox') ? '' : $props['content'];
}

// Link button styling
if ($link && ($props['link_text'] || $element['link_text'])) {
    $link->attr([
        'class' => [
            'fs-switcher__item-link',
            'uk-{link_style: link-(muted|text)}',
            'uk-button uk-button-{!link_style: |link-muted|link-text} [uk-button-{link_size}] [uk-width-1-1 {@link_fullwidth}]',

            // Deprecated remove in v.2.0.0
            'el-link',
        ],
        // JS hook (used in fs-switcher.js)
        'data-fs-switcher-item-link' => $element['switcher_mode'] === 'hover' && $element['switcher_mode_hover_trigger_click'],
    ]);
}

return $link;