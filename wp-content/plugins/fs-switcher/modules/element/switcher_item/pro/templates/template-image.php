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
/** @var $element */
/** @var $props */
/** @var $__dir */
/** @var $helper */

if (!$props['image']) {
    return;
}

// Overrides
$element['image_text_color'] = $props['image_text_color'] ?: $element['image_text_color'];

// Image
$image = $this->el('image', [
    'class' => [
        'fs-switcher__item-image',
        'uk-border-{image_border} {@!image_transition}',
        'uk-box-shadow-{image_box_shadow} {@!image_transition}',
        'uk-box-shadow-hover-{image_hover_box_shadow} {@!image_transition}' => $props['link'] && $element['image_link'],
        'uk-transition-{image_transition} uk-transition-opaque' => $props['link'] && $element['image_link'],
        'uk-text-{image_svg_color} {@image_svg_inline}' => $this->isImage($props['image']) === 'svg',
        'uk-inverse-{image_text_color}',
        'uk-margin[-{image_margin}]-top {@!image_margin: remove} {@!image_box_decoration} {@!image_transition}' => $element['image_align'] === 'bottom',
        '{image_visibility}',

        // Deprecated remove in v.2.0.0
        'el-image',
    ],
    'src' => $props['image'],
    'alt' => $props['image_alt'],
    'title' => $props['image_title'],
    'width' => $element['image_width'] ?? 300,
    'height' => $element['image_height'],
    'focal_point' => $props['image_focal_point'],
    'uk-svg' => $element['image_svg_inline'],
    'loading' => $element['image_loading'] ? false : null,
    'fetchpriority' => $element['image_fetchpriority'] ? 'high' : null,
    'decoding' => $element['image_decoding'] ? 'async' : null,
    'thumbnail' => !$element['image_cache_disable'],
    strip_tags($props['image_attributes'] ?? ''),
]);

// Image Element
$props['image'] = $image($element);

// Transition
if ($element['image_transition']) {
    $transition_toggle = $this->el('div', [
        'class' => [
            'uk-inline-clip [uk-transition-toggle {@image_link}]',
            'uk-border-{image_border}',
            'uk-box-shadow-{image_box_shadow}',
            'uk-box-shadow-hover-{image_hover_box_shadow}' => $props['link'] && $element['image_link'],
            'uk-margin[-{image_margin}]-top {@!image_margin: remove} {@!image_box_decoration}' => $element['image_align'] === 'bottom',
        ],
    ]);
    $props['image'] = $transition_toggle($element, $props['image']);
}

// Box Decoration
if ($element['image_box_decoration']) {
    $decoration = $this->el('div', [
        'class' => [
            'uk-box-shadow-bottom {@image_box_decoration: shadow}',
            'tm-mask-default {@image_box_decoration: mask}',
            'tm-box-decoration-{image_box_decoration: default|primary|secondary}',
            'tm-box-decoration-inverse {@image_box_decoration_inverse} {@image_box_decoration: default|primary|secondary}',
            'uk-inline {@!image_box_decoration: |shadow}',
            'uk-margin[-{image_margin}]-top {@!image_margin: remove} {@image_align: bottom}',
        ],
    ]);
    $props['image'] = $decoration($element, $props['image']);
}

// Show Sublayout
$showSublayout = fn($position, $align) => $element['show_sublayout'] && $element['sublayout_position'] === $position
&& $element['sublayout_align'] === $align ? $this->render("$__dir/sublayout/template-sublayout", ['props' => $props])
    : '';

// Show Grid
$showGrid = fn($position) => !empty($helper[$position])
    ? $this->render("$__dir/grids/template-grid", ['props' => $props, 'grids' => $helper[$position]])
    : '';

// Render Layout Blocks
$renderBlock = static fn($position) => $showSublayout($position, 'top') .
    $showGrid($position) . $showSublayout($position, 'bottom');

// Output
echo $renderBlock('item-image-cell-top') . $props['image'] . $renderBlock('item-image-cell-bottom');