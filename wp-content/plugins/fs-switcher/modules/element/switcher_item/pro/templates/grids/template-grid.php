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
/** @var $grids */
/** @var $__dir */

// Exit if the $grid variable is not set
if (!$grids) {
    return;
}

?>

<?php foreach ($grids as $g => $fieldsets) : ?>

    <?php
    // Overrides settings [Grid]
    foreach (['visibility', 'panel_style'] as $key) {
        if (!empty($props["grid_{$g}_$key"])) {
            $element["grid_{$g}_$key"] = $props["grid_{$g}_$key"];
        }
    }

    // Grid Container
    ${"grid_{$g}_container"} = $this->el('div', [
        'class' => [
            "fs-switcher__grid-container el-grid-container--$g",
            'uk-panel',
            "uk-margin {@_grids_helper}",
            $element["grid_{$g}_visibility"],

            // Deprecated remove in v2.0.0
            "fs-switcher-nested-$g-container",
        ],
        'style' => ["border: 1px dashed #ddd; {@_grids_helper}"],
    ]);

    // Grid
    ${"grid_$g"} = $this->el('div', [
        'class' => [
            "fs-switcher__grid el-grid--$g",
            "uk-slider-items {@grid_{$g}_slider}",
            "uk-child-width-{grid_{$g}_default}",
            "uk-child-width-{grid_{$g}_small}@s",
            "uk-child-width-{grid_{$g}_medium}@m",
            "uk-child-width-{grid_{$g}_large}@l",
            "uk-child-width-{grid_{$g}_xlarge}@xl",
            "uk-text-{grid_{$g}_text_align}[@{grid_{$g}_text_align_breakpoint} [uk-text-{grid_{$g}_text_align_fallback}]]",
            "uk-flex-center {@grid_{$g}_column_align}{@!grid_{$g}_slider}",
            "uk-flex-middle {@grid_{$g}_row_align}",
            "uk-grid-column-{grid_{$g}_column_gap}",
            "uk-grid-row-{grid_{$g}_row_gap}",
            "uk-grid-divider {@grid_{$g}_divider}{@!grid_{$g}_column_gap:collapse}{@!grid_{$g}_row_gap:collapse}",
            "uk-margin[-{grid_{$g}_margin}]-top {@!grid_{$g}_margin: remove} {@grid_{$g}_position: above-content|below-content|item-bottom|item-image-cell-bottom}",
            "uk-margin[-{grid_{$g}_margin}]-bottom {@!grid_{$g}_margin: remove} {@grid_{$g}_position: item-top|item-image-cell-top}",
            "uk-grid-match",

            // Deprecated remove in v2.0.0
            "fs-switcher-nested-$g",
        ],
        'uk-grid' => true,
        'uk-grid-checked' => $element["grid_{$g}_panel_style"] === 'tile-checked' ?
            'uk-tile-default,uk-tile-muted' : false,
    ]);

    // Grid Highlighter (helper)
    $highlighter = $element['_grids_helper'] ? $this->el('span', [
        'class' => ['uk-flex uk-flex-center uk-text-meta'],
        'style' => ['border-bottom: 1px dashed #ddd; padding: 10px; background: #f7f7f7; color: #6c6d74;'],
    ]) : null;

    // Grid Divider (top)
    $divider = $element["grid_{$g}_divider_top"]
        ? $this->el('hr', [
            'class' => [
                'fs-switcher__grid-divider',
                $element["grid_{$g}_margin"] !== 'remove' && in_array(
                    $element["grid_{$g}_position"],
                    ['item-top', 'item-image-cell-top'],
                    true,
                )
                    ? "uk-margin[-{grid_{$g}_margin}]-bottom uk-margin-remove-top"
                    : "uk-margin[-{grid_{$g}_margin}]-top uk-margin-remove-bottom",
            ],
        ])
        : null;

    // Slider Container
    ${"slider_{$g}_container"} = $element["grid_{$g}_slider"] ? $this->el('div', [
        'class' => [
            "uk-slider-container",
            "uk-slider-container-offset {@grid_{$g}_panel_style}{@grid_{$g}_panel_card_offset}{grid_{$g}_panel_style: card-.*}",
        ],
        'uk-slider' => $this->expr([
            'sets: {slider_sets};',
            'center: {slider_center};',
            'finite: {slider_finite};',
            'velocity: {slider_velocity};',
            'autoplay: {slider_autoplay}; [pauseOnHover: false; {@!slider_autoplay_pause}] [autoplayInterval: {slider_autoplay_interval}000;]',
        ], $element) ?: true,
    ]) : null;

    // Slider
    $slider = ${"slider_{$g}_container"} ? $this->el('div', [
        'class' => ['uk-position-relative', 'uk-visible-toggle {@slider_slidenav} {@slider_slidenav_hover}'],
        'tabindex' => ['-1 {@slider_slidenav} {@slider_slidenav_hover}'],
    ]) : null;
    ?>

    <?= $divider && in_array($element["grid_{$g}_position"],
        ['above-content', 'below-content', 'item-bottom', 'item-image-cell-bottom']) ? $divider($element, '') : '' ?>

    <?= ${"grid_{$g}_container"}($element) ?>
    <?= $highlighter ? $highlighter($element, "— Grid $g —") : '' ?>

    <?php if ($element['show_sublayout'] && $element['sublayout_position'] === "grid_$g" && $element['sublayout_align'] === 'top') : ?>
        <?= $this->render(dirname($__dir) . '/sublayout/template-sublayout') ?>
    <?php endif ?>

    <?= $slider ? ${"slider_{$g}_container"}($element) . $slider($element) : '' ?>

    <?= ${"grid_$g"}($element,
        $this->render("$__dir/template-grid-content", ['props' => $props, 'grid' => $g, 'fieldsets' => $fieldsets])) ?>

    <?php if ($slider) : ?>
        <?= $element['slider_slidenav'] ? $this->render("$__dir/template-slider-slidenav") : '' ?>
        <?= $slider->end() ?>
        <?= $element['slider_dotnav'] ? $this->render("$__dir/template-slider-dotnav") : '' ?>
        <?= ${"slider_{$g}_container"}->end() ?>
    <?php endif ?>

    <?php if ($element['show_sublayout'] && $element['sublayout_position'] === "grid_$g" && $element['sublayout_align'] === 'bottom') : ?>
        <?= $this->render(dirname($__dir) . '/sublayout/template-sublayout') ?>
    <?php endif ?>

    <?= ${"grid_{$g}_container"}->end() ?>

    <?= $divider && in_array($element["grid_{$g}_position"], ['item-top', 'item-image-cell-top']) ?
        $divider($element, '') : '' ?>

<?php endforeach ?>