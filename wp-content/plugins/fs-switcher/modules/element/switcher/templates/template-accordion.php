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
/** @var $props */
/** @var $progress */
/** @var $builder */
/** @var $children */
/** @var $__dir */

// Accordion
$accordion = $this->el('div', [
    'class' => [
        'fs-switcher__nav fs-switcher__nav--accordion',
        // YOOtheme Pro 5 Margins
        ...(($props['_yootheme_v5'] ?? false) === true ? [
            'uk-accordion-default'
        ] : []),

        // Deprecated remove in v.2.0.0
        'el-nav',
    ],
    'uk-accordion' => [
        'active: 0; {@!switcher_accordion_collapsible}',
        'multiple: {switcher_accordion_multiple}; {@!switcher_autoplay}',
        'collapsible: {0};' => $props['switcher_accordion_collapsible'],
    ],
    'uk-lightbox' => ($props['lightbox'] ?? false) ? [
        'toggle: a.fs-switcher__item-link--lightbox[data-type];',
        'animation: {lightbox_animation};',
        'nav: {lightbox_nav}; slidenav: false;',
        'delay-controls: 0;' => $props['lightbox_controls'],
        'counter: true;' => $props['lightbox_counter'],
        'bg-close: false;' => !$props['lightbox_bg_close'],
    ] : false,

    // JS hooks (used in fs-switcher.js)
    'data-fs-switcher-nav',
    'data-fs-switcher-hover-area' => $props['switcher_autoplay'] && $props['switcher_autoplay_pause'],
]);

// Title
$title = $this->el('a', [
    'href' => "#",
    'class' => ['fs-switcher__nav-item-link fs-switcher__nav-item-title', 'uk-accordion-title uk-position-relative'],
    'data-fs-switcher-nav-link', // JS hook (used in fs-switcher.js)
]);

// Icon (YOOtheme Pro 5)
$accordion_icon = ($props['_yootheme_v5'] ?? false) === true ? $this->el('span', [
    'class' => ['fs-switcher__nav-item-icon'],
    'uk-accordion-icon',
]) : null;

// Content
$content = $this->el('div', [
    'class' => [
        'fs-switcher__nav-item-content uk-accordion-content uk-margin-remove-first-child',
        'uk-padding[-{switcher_accordion_padding}]',
    ],
]);

// Grid
$grid = $props['switcher_accordion_thumbnail'] ? $this->el('div', [
    'class' => [
        'fs-switcher__nav-item-grid',
        'uk-grid-{switcher_thumbnail_grid_column_gap}',
        'uk-child-width-expand uk-flex-nowrap uk-flex-middle',
    ],
    'uk-grid' => true,
]) : null;

// Image Cell
$image_cell = $grid ? $this->el('div', ['class' => ['fs-switcher__nav-item-image-cell uk-width-auto']]) : null;

// Image Wrapper
$image_wrapper = $grid ? $this->el('div', ['class' => ['fs-switcher__nav-item-image-wrapper', 'uk-inline-clip']]) : null;

// Label Cell
$label_cell = $grid ? $this->el('div', ['class' => ['fs-switcher__nav-item-label-cell uk-margin-medium-right']]) : null;

?>

<?= $progress && in_array($props['switcher_autoplay_progress_position'], ['nav-top', 'item-top']) ? $progress : '' ?>
<?= $accordion($props) ?>

<?php foreach ($children as $i => $child) {
    $item = $this->el('div', [
        'id' => $child->props['id'] ?: null,
        'class' => ['fs-switcher__nav-item', 'el-nav-item--' . ++$i, $child->props['class'] ?? ''],
        'data-index' => $i,
        $child->props['attributes'] ?? '',
    ]);
    echo $item($props);
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
    echo $title($props, ['href' => $child->props['tab_link']]);
    if ($image && $child->props['nav_title']) {
        echo $grid($props);
        echo $image_cell($props, $image_wrapper($props, $image($props) . ($thumbnail_hover ? $thumbnail_hover($props) : '')));
        echo $label_cell($props, $child->props['nav_title']);
        echo $grid->end();
    } else {
        echo $child->props['nav_title'];
    }
    echo $accordion_icon ? $accordion_icon($props, '') : '';
    echo $title->end();
    echo $content($props, $builder->render($child, ['element' => $props]));
    echo $item->end();
} ?>

<?= $accordion->end() ?>
<?= $progress && in_array($props['switcher_autoplay_progress_position'], ['nav-bottom', 'item-bottom']) ? $progress : '' ?>