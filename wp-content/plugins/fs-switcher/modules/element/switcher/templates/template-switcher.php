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
/** @var $progress */
/** @var $__dir */
/** @var $builder */
/** @var $children */

// Nav Grid (only for left/right positions)
$grid = in_array($props['switcher_position'], ['left', 'right']) ? $this->el('div', [
    'class' => [
        'fs-switcher__grid',
        $props['switcher_grid_column_gap'] === $props['switcher_grid_row_gap']
            ? 'uk-grid-{switcher_grid_column_gap}'
            : '[uk-grid-column-{switcher_grid_column_gap}] [uk-grid-row-{switcher_grid_row_gap}]',
        'uk-child-width-expand',
        'uk-flex-middle {@switcher_vertical_align}',
        'uk-grid-divider {@switcher_grid_divider}',
    ],
    'uk-grid' => true,
]) : null;

// Nav Cell
$cell = $grid ? $this->el('div', [
    'class' => [
        'fs-switcher__nav-container',
        'uk-width-{switcher_grid_width}@{switcher_grid_breakpoint}',
        'uk-flex-last@{switcher_grid_breakpoint} {@switcher_position: right}',
    ],
]) : null;

// Content Cell
$content = $this->el('div', [
    'class' => ['fs-switcher__items-container', 'uk-switcher'],
    'id' => ['{connect}'],
    'uk-height-match' => ['row: false {@switcher_height}'],
    'uk-lightbox' => ($props['lightbox'] ?? false) ? [
        'toggle: a.fs-switcher__item-link--lightbox[data-type];',
        'animation: {lightbox_animation};',
        'nav: {lightbox_nav}; slidenav: false;',
        'delay-controls: 0;' => $props['lightbox_controls'],
        'counter: true;' => $props['lightbox_counter'],
        'bg-close: false;' => !$props['lightbox_bg_close'],
    ] : false,

    // JS hooks (used in fs-switcher.js)
    'data-fs-switcher-items',
    'data-fs-switcher-hover-area' => $props['switcher_autoplay'] && $props['switcher_autoplay_pause'],
]);

// Sticky
$sticky = $grid && $props['position_sticky'] ? $this->el('div', [
    'class' => ['fs-switcher__nav-sticky', 'uk-panel uk-position-z-index'],
    'uk-sticky' => $this->expr([
        'offset: {position_sticky_offset};',
        'media: @{position_sticky_breakpoint};',
        'end: !.fs-switcher;',
    ], $props) ?: true,
]) : null;

// Render Items
$renderItems = function () use ($children, $props, $builder) {
    foreach ($children as $i => $child) {
        echo $this->el('div', [
            'id' => $child->props['id'] ?: null,
            'class' => [
                'fs-switcher__item',
                'el-item--' . ($i + 1),
                'uk-margin-remove-first-child',
                $child->props['class'] ?? '',

                // Deprecated remove in v.2.0.0
                'el-item',
            ],
            'data-index' => $i,
            $child->props['attributes'] ?? '',
        ])($props, $builder->render($child, ['element' => $props]));
    }
};

// Progress Helper
$showProgress = static fn($pos) => $progress && $props['switcher_autoplay_progress_position'] === $pos ? $progress : '';

// Render Navigation
$renderNav = function ($positions) use ($props, $__dir, $showProgress) {
    if (!in_array($props['switcher_position'], (array)$positions, true)) {
        return '';
    }
    return $showProgress('nav-top') .
        $this->render("$__dir/template-switcher-nav", compact('props')) .
        $showProgress('nav-bottom');
};
?>

<?= $showProgress('item-top') ?>
<?php if ($grid) : ?>
    <?= $grid($props) ?>
    <?= $cell($props) ?>
    <?= $sticky ? $sticky($props) : '' ?>
    <?= $renderNav(['left', 'right']) ?>
    <?= $sticky ? $sticky->end() : '' ?>
    <?= $cell->end() ?>
    <?= $content($props) ?>
    <?php $renderItems() ?>
    <?= $content->end() ?>
    <?= $grid->end() ?>
<?php else : ?>
    <?= $renderNav('top') ?>
    <?= $content($props) ?>
    <?php $renderItems() ?>
    <?= $content->end() ?>
    <?= $renderNav('bottom') ?>
<?php endif ?>
<?= $showProgress('item-bottom') ?>