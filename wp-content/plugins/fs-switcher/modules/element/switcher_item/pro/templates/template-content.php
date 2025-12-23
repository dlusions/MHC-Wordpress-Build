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
/** @var $link */

// Title
$title = $this->el($element['title_element'] ?? 'div', [
    'class' => [
        'fs-switcher__item-title',
        'uk-{title_style}',
        'uk-heading-{title_decoration}',
        'uk-font-{title_font_family}',
        'uk-text-{title_color} {@!title_color: background}',
        'uk-margin[-{title_margin}]-top {@!title_margin: remove}',
        'uk-margin-remove-bottom [uk-margin-{title_margin: remove}-top]' => !in_array($element['title_style'],
                [null, '', 'text-meta', 'text-lead', 'text-small', 'text-large'], true) || $element['title_element'] !== 'div',

        // Deprecated remove in v.2.0.0
        'el-title',
    ],
]);

// Meta
$meta = $this->el($element['meta_element'] ?? 'div', [
    'class' => [
        'el-item__meta',
        'uk-{meta_style}',
        'uk-text-{meta_color}',
        'uk-heading-{meta_decoration}',
        'uk-margin[-{meta_margin}]-top {@!meta_margin: remove}',
        'uk-margin-remove-bottom [uk-margin-{meta_margin: remove}-top]' => !in_array($element['meta_style'],
                [null, '', 'text-meta', 'text-lead', 'text-small', 'text-large'], true) || $element['meta_element'] !== 'div',
        '{meta_visibility}',

        // Deprecated remove in v.2.0.0
        'el-meta',
    ],
]);

// Content
$content = $this->el($element['content_element'] ?? 'div', [
    'class' => [
        'fs-switcher__item-content uk-panel',
        'uk-{content_style}',
        'uk-text-{content_color}',
        '[uk-text-left{@content_align}]',
        'uk-dropcap {@content_dropcap}',
        'uk-column-{content_column}[@{content_column_breakpoint}]',
        'uk-column-divider {@content_column} {@content_column_divider}',
        'uk-margin[-{content_margin}]-top {@!content_margin: remove}',
        'uk-margin-remove-bottom [uk-margin-{content_margin: remove}-top]' => !in_array($element['content_style'],
                [null, '', 'text-meta', 'text-lead', 'text-small', 'text-large'], true) || $element['content_element'] !== 'div',
        '{content_visibility}',

        // Deprecated remove in v.2.0.0
        'el-content',
    ],
]);

// Limit Output
foreach (['title', 'meta', 'content'] as $field) {
    if (!empty($element["{$field}_limit"]) && !empty($props[$field])) {
        $limit = (int)($element["{$field}_limit_length"] ?? 0);
        $plain = strip_tags($props[$field]);
        $props[$field] = ($limit > 0 && mb_strlen($plain, 'UTF-8') > $limit) ? mb_substr($plain, 0, $limit,
                'UTF-8') . '...' : $props[$field];
    }
}

// Link
$link_container = $this->el('div', [
    'class' => [
        'fs-switcher__item-link-container',
        'uk-margin[-{link_margin}]-top {@!link_margin: remove}',
        '{link_visibility}',
    ],
]);

// Title Grid
$grid = $this->el('div', [
    'class' => [
        'fs-switcher__item-title-grid',
        'uk-child-width-expand',
        $element['title_grid_column_gap'] === $element['title_grid_row_gap'] ? 'uk-grid-{title_grid_column_gap}' : '[uk-grid-column-{title_grid_column_gap}] [uk-grid-row-{title_grid_row_gap}]',
        'uk-margin[-{title_margin}]-top {@!title_margin: remove} {@image_align: top}' => !$props['meta'] || $element['meta_align'] !== 'above-title',
        'uk-margin[-{meta_margin}]-top {@!meta_margin: remove} {@image_align: top} {@meta_align: above-title}' => $props['meta'],
    ],
    'uk-grid' => true,
]);

// Title Cell
$cell_title = $this->el('div', [
    'class' => [
        'fs-switcher__item-title-cell',
        'uk-width-{title_grid_width}[@{title_grid_breakpoint}]',
        'uk-margin-remove-first-child',
    ],
]);

// Content Cell
$cell_content = $this->el('div', ['class' => ['fs-switcher__item-content-cell', 'uk-margin-remove-first-child']]);

// Helper function to check if a value is set (treats "0" as valid)
$hasValue = static fn($val): bool => $val !== null && trim((string)$val) !== '';

// Show Meta
$showMeta = static fn($align) => $hasValue($props['meta']) && $element['meta_align'] === $align
    ? $meta($element, $props['meta'])
    : '';

// Show Sublayout
$showSublayout = fn($position, $align) => $element['show_sublayout']
&& $element['sublayout_position'] === $position
&& $element['sublayout_align'] === $align ? $this->render("$__dir/sublayout/template-sublayout", ['props' => $props])
    : '';

// Show Grid
$showGrid = fn($position) => !empty($helper[$position])
    ? $this->render("$__dir/grids/template-grid", ['props' => $props, 'grids' => $helper[$position]])
    : '';

// Show Link
$showLink = static fn() => !$element['link_bellow_image'] && $props['link'] && ($props['link_text'] || $element['link_text'])
    ? $link_container($element, $link($element, $props['link_text'] ?: $element['link_text']))
    : '';

// Title Layout Flag
$isLeftTitle = $hasValue($props['title']) && $element['title_align'] === 'left';

?>

<?= $isLeftTitle ? $grid($element) . $cell_title($element) : '' ?>
<?= $showMeta('above-title') ?>
<?php if ($hasValue($props['title'])) : ?>
    <?= $title($element) ?>
    <?php if ($element['title_color'] === 'background') : ?>
        <span class="uk-text-background"><?= $props['title'] ?></span>
    <?php elseif ($element['title_decoration'] === 'line') : ?>
        <span><?= $props['title'] ?></span>
    <?php else : ?>
        <?= $props['title'] ?>
    <?php endif ?>
    <?= $title->end() ?>
<?php endif ?>
<?= $showMeta('below-title') ?>
<?= $isLeftTitle ? $cell_title->end() . $cell_content($element) : '' ?>
<?= $showSublayout('above-content', 'top') ?>
<?= $showMeta('above-content') ?>
<?= $showGrid('above-content') ?>
<?= $showSublayout('above-content', 'bottom') ?>
<?= $hasValue($props['content']) ? $content($element, $props['content']) : '' ?>
<?= $showSublayout('below-content', 'top') ?>
<?= $showGrid('below-content') ?>
<?= $showMeta('below-content') ?>
<?= $showSublayout('below-content', 'bottom') ?>
<?= $showLink() ?>
<?= $isLeftTitle ? $cell_content->end() . $grid->end() : '' ?>