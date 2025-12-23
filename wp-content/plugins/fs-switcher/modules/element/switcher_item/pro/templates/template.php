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
/** @var $helper */
/** @var $__dir */

// Display
if (!$element['show_title']) {
    $props['title'] = '';
}

// Auto linking modal and lightbox
if ($element['show_link']) {
    if ($element['show_sublayout'] && $element['sublayout_mode'] === "modal" && $element['sublayout_modal_group'] === "all" && $props['link_toggle'] && $props['link_toggle_modal_integration']) {
        $props['link_modal_id'] = "fs-switcher-pro-modal-{$this->uid()}";
        $props['link'] = "#{$props['link_modal_id']}";
    } elseif ($element['lightbox'] && $props['image'] && !$props['link']) {
        $props['link'] = $props['image'];
    }
}

// Item
$el = $props['item_element'] ? $this->el($props['item_element']) : null;

// Image
$props['image'] = $this->render("$__dir/template-image", compact('props', 'helper'));

// Grid
$grid = $props['image'] && in_array($element['image_align'], ['left', 'right']) ? $this->el('div', [
    'class' => [
        'fs-switcher__item-image-grid',
        'uk-child-width-expand',
        $element['image_grid_column_gap'] === $element['image_grid_row_gap'] ? 'uk-grid-{image_grid_column_gap}' : '[uk-grid-column-{image_grid_column_gap}] [uk-grid-row-{image_grid_row_gap}]',
        'uk-flex-middle {@image_vertical_align}',
    ],
    'uk-grid' => true,
]) : null;

// Cell image
$cell_image = $grid ? $this->el('div', [
    'class' => [
        'fs-switcher__item-image-cell',
        'uk-width-{image_grid_width}@{image_grid_breakpoint}',
        'uk-flex-last@{image_grid_breakpoint} {@image_align: right}',
    ],
]) : null;

// Cell content
$cell_content = $grid ? $this->el('div', [
    'class' => [
        $element['title_align'] === 'left' ? 'fs-switcher__item-content-container' : 'fs-switcher__item-content-cell',
        'uk-margin-remove-first-child',
    ],
]) : null;

// Link container
$link_container = $this->el('div', [
    'class' => [
        'fs-switcher__item-link-container',
        'uk-margin[-{link_margin}]-top {@!link_margin: remove}',
        '{link_visibility}',

        // Deprecated remove in v.2.0.0
        'el-link-container',
    ],
]);

// Link
$link = include __DIR__ . "/template-link.php";
?>

<?= $el ? $el($element) : '' ?>
<?php if (!empty($helper['item-top'])): ?>
    <?= $this->render("$__dir/grids/template-grid", ['props' => $props, 'grids' => $helper['item-top']]) ?>
<?php endif ?>

<?php if ($grid) : ?>
    <?= $grid($element) ?>
    <?= $cell_image($element) ?>
    <?= $props['image'] ?>
    <?php if ($element['link_bellow_image'] && $props['link'] && ($props['link_text'] || $element['link_text'])) : ?>
        <?= $link_container($element, $link($element, $props['link_text'] ?: $element['link_text'])) ?>
    <?php endif ?>
    <?= $cell_image->end() ?>
    <?= $cell_content($element, $this->render("$__dir/template-content", compact('props', 'helper', 'link'))) ?>
    <?= $grid->end() ?>
<?php else : ?>
    <?= $element['image_align'] === 'top' ? $props['image'] : '' ?>
    <?= $this->render("$__dir/template-content", compact('props', 'helper', 'link')) ?>
    <?= $element['image_align'] === 'bottom' ? $props['image'] : '' ?>
<?php endif ?>

<?php if (!empty($helper['item-bottom'])): ?>
    <?= $this->render("$__dir/grids/template-grid", ['props' => $props, 'grids' => $helper['item-bottom']]) ?>
<?php endif ?>
<?= $el ? $el->end() : '' ?>