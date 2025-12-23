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

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $children */
/** @var $props */
/** @var $thumbnail */
/** @var $thumbnail_hover */
/** @var $icon */

// Navigation
$nav = $this->el('ul', [
    'class' => [
        'fs-switcher__nav fs-switcher__nav--switcher',
        'fs-switcher__nav--{switcher_style}',
        'uk-{switcher_style: thumbnav}{@!switcher_thumbnail_label}',
        'uk-nav-default {@switcher_style: thumbnav} {@switcher_thumbnail_label}',
        'uk-nav-divider {@switcher_style: subnav-divider}',
        'uk-margin[-{switcher_margin}] {@switcher_position: top|bottom}',

        // Deprecated remove in v.2.0.0
        'el-nav',
    ],
    $props['switcher_style'] === 'tab' ? 'uk-tab' : 'uk-switcher' => [
        'connect: #{connect};',
        'itemNav: #{item_nav};',
        'animation: uk-animation-{switcher_animation};',
        'media: @{switcher_grid_breakpoint} {@switcher_position: left|right} {@switcher_style: tab};',
        'swiping: false; {@switcher_swipe_disable}{@!switcher_style: accordion}',
    ],
    'uk-margin' => $props['switcher_style'] === 'thumbnav' && !$props['switcher_thumbnav_shrink'] && !$props['switcher_nowrap'],
    'data-fs-switcher-nav', // JS hook (used in fs-switcher.js)
]);

// Horizontal Layout
$nav_horizontal = [
    'fs-switcher__nav--horizontal',
    'uk-subnav {@switcher_style: subnav-.*}',
    'uk-{switcher_style: subnav.*}',
    'uk-{switcher_style: thumbnav}{@switcher_thumbnail_label}',
    'uk-tab-{switcher_position: bottom} {@switcher_style: tab}',
    'uk-flex-{switcher_align: right|center}',
    'uk-child-width-expand {@switcher_align: justify}',
    'uk-flex-nowrap' => $props['switcher_nowrap'] || (($props['switcher_style'] === 'thumbnav') && $props['switcher_thumbnav_shrink']),
    'uk-flex-middle' => (in_array($props['switcher_position'], ['top', 'bottom']) || $props['switcher_style'] === 'thumbnav')
        && ($props['switcher_thumbnail_label'] && !$props['switcher_thumbnail_label_position']),
    'uk-flex-bottom {@switcher_style:thumbnav}{@switcher_thumbnail_label_position:top}',
];

// Vertical Layout
$nav_vertical = [
    'fs-switcher__nav--vertical',
    'uk-nav uk-nav-[primary {@switcher_style_primary}][default {@!switcher_style_primary}] [uk-text-left {@text_align}] {@switcher_style: subnav.*}',
    'uk-nav {@switcher_style: thumbnav} {@switcher_thumbnail_label}',
    'uk-tab-{switcher_position} {@switcher_style: tab}',
    'uk-thumbnav-vertical {@switcher_style: thumbnav}{@!switcher_thumbnail_label}',

    // For horizontal aligned nav after breakpoint
    'uk-flex-nowrap' => $props['switcher_nowrap'],
];

// Layout Switcher
$nav_switcher = in_array($props['switcher_position'], ['top', 'bottom'])
    ? ['class' => $nav_horizontal]
    : [
        'class' => $nav_vertical,
        'uk-toggle' => $props['switcher_style'] !== 'tab' ? [
            "cls: {$this->expr(array_merge($nav_vertical, $nav_horizontal), $props)};",
            'mode: media;',
            'media: @{switcher_grid_breakpoint};',
        ] : false,
    ];

// Container
$container = $props['switcher_nowrap'] && !(($props['switcher_style'] === 'thumbnav') && $props['switcher_thumbnav_shrink']) ?
    $this->el('div', [
        'class' => ['uk-overflow-fade-horizontal uk-panel'],
        'uk-overflow-fade' => true,
    ]) : null;

($container ?: $nav)->attr('class', [
    'uk-margin[-{switcher_margin}] {@switcher_position: top|bottom}',
]);

// Item
$li = $this->el('li', [
    'class' => [
        'fs-switcher__nav-item',
        'uk-transition-toggle {@switcher_style: thumbnav} {@switcher_thumbnail_hover}',
    ],
    'style' => $this->expr([
        '[width: {switcher_thumbnail_width}px; {@switcher_style: thumbnav}{@switcher_thumbnail_label}{@switcher_thumbnail_label_position:top|bottom}]',
    ], $props) ?: false,
]);

// Link
$a = $this->el('a', ['href' => "#", 'class' => ['fs-switcher__nav-item-link'], 'data-fs-switcher-nav-link']);

// Grid
$grid = $props['switcher_style'] === 'thumbnav' ? $this->el('div', [
    'class' => [
        'fs-switcher__nav-item-grid',
        'uk-grid-small uk-child-width-expand uk-flex-nowrap uk-flex-middle',
        'uk-flex-column {@switcher_style: thumbnav}{@switcher_thumbnail_label_position}',
        'uk-text-center {@switcher_style: thumbnav}{@switcher_thumbnail_label_position}',
        'uk-flex-column-reverse {@switcher_style: thumbnav}{@switcher_thumbnail_label_position:top}',
    ],
    'uk-grid' => true,
]) : null;

// Image Cell
$image_cell = $grid ? $this->el('div', ['class' => ['fs-switcher__nav-item-image-cell']]) : null;

// Image Wrapper
$image_wrapper = $grid ? $this->el('div', ['class' => ['fs-switcher__nav-item-image-wrapper', 'uk-inline-clip']]) : null;

// Label Cell
$label_cell = $grid ? $this->el('div', [
    'class' => [
        'fs-switcher__nav-item-label-cell',
        '{switcher_thumbnail_label_visibility} {@switcher_style: thumbnav}',
    ],
]) : null;

?>

<?= $container ? $container($props) : '' ?>
<?= $nav($props, $nav_switcher) ?>
<?php foreach ($children as $i => $child) {
    $thumbnail = $thumbnail_hover = $icon = null;
    if ($grid) {
        $thumbnail_icon = $child->props['thumbnail_icon'];
        $thumbnail_image = !$thumbnail_icon || ($child->props['thumbnail'] ?? false)
            ? (($child->props['thumbnail'] ?? false) ?: ($child->props['image'] ?? false))
            : null;
        if ($thumbnail_image || $thumbnail_icon) {
            include __DIR__ . "/template-thumbnail.php";
        }
    }
    $image = $thumbnail ?: $icon;
    $hasLabel = $props['switcher_thumbnail_label'] && $child->props['nav_title'];
    echo $li($props, ['class' => ['el-nav-item--' . ++$i], 'data-index' => $i]);
    echo $a($props, ['href' => $child->props['tab_link']]);
    if ($image) {
        echo $hasLabel ? $grid($props) : '';
        echo $image_cell($props, $hasLabel ? ['class' => ['uk-width-auto']] : []);
        echo $image_wrapper($props, $image($props) . ($thumbnail_hover ? $thumbnail_hover($props) : ''));
        echo $image_cell->end();
        echo $hasLabel ? $label_cell($props, $child->props['nav_title']) . $grid->end() : '';
    } else {
        echo $child->props['nav_title'];
    }
    echo $a->end() . $li->end();
} ?>
<?= $nav->end() ?>
<?= $container ? $container->end() : '' ?>