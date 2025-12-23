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
/** @var $element */
/** @var $props */
/** @var $grid */
/** @var $fieldsets */

// Exit if the $grid and $fieldsets variables are not set
if (!$grid && !$fieldsets) {
    return;
}

// Shortcuts
$g = $grid;

// Helper function to check if a value is set (treats "0" as valid)
$hasValue = static fn($val): bool => $val !== null && trim((string)$val) !== '';

// Overrides panel settings [Grid]
foreach (['panel_style', 'panel_padding'] as $key) {
    if (!empty($props["grid_{$g}_$key"])) {
        $element["grid_{$g}_$key"] = $props["grid_{$g}_$key"];
    }
}

?>

<?php foreach ($fieldsets as $f => $fieldset) : ?>

    <?php
    // Visibility override [Fieldsets]
    if (!empty($props["fieldset_{$f}_visibility"])) {
        $element["fieldset_{$f}_visibility"] = $props["fieldset_{$f}_visibility"];
    }

    // Mixed width overrides [Fieldsets]
    if ($element['enable_fieldsets_mixed_width']) {
        foreach (['default', 'small', 'medium', 'large', 'xlarge'] as $breakpoint) {
            if (!empty($props["fieldset_{$f}_mixed_width_$breakpoint"])) {
                $element["fieldset_{$f}_mixed_width_$breakpoint"] = $props["fieldset_{$f}_mixed_width_$breakpoint"];
            }
            if (empty($element["fieldset_{$f}_mixed_width_$breakpoint"])) {
                $element["fieldset_{$f}_mixed_width_$breakpoint"] = $element["grid_{$g}_$breakpoint"];
            }
        }
    }

    // Fieldset
    ${"fieldset_$f"} = $this->el('div', [
        'class' => [
            "fs-switcher__fieldset el-fieldset--$f",
            "{fieldset_{$f}_visibility}",
            ...($element['enable_fieldsets_mixed_width']
                ? [
                    "uk-width-{fieldset_{$f}_mixed_width_default}",
                    "uk-width-{fieldset_{$f}_mixed_width_small}@s",
                    "uk-width-{fieldset_{$f}_mixed_width_medium}@m",
                    "uk-width-{fieldset_{$f}_mixed_width_large}@l",
                    "uk-width-{fieldset_{$f}_mixed_width_xlarge}@xl",
                ] : []
            ),

            // Deprecated remove in v.2.0.0
            "fs-switcher-fieldset fs-switcher-fieldset-$f",
        ],
    ]);

    // Panel
    ${"panel_$f"} = $element["grid_{$g}_panel_style"] ? $this->el('div', [
        'class' => [
            "fs-switcher__fieldset-panel",
            "uk-panel [uk-{grid_{$g}_panel_style: tile-.*}] {@grid_{$g}_panel_style: |tile-.*}",
            "uk-card uk-{grid_{$g}_panel_style: card-.*} [uk-card-{!grid_{$g}_panel_padding: |default}]",
            "uk-tile-hover {@grid_{$g}_panel_style: tile-.*} " => $props["link_$f"],
            "uk-card-hover {@!grid_{$g}_panel_style: |card-hover|tile-.*} " => $props["link_$f"],
            "uk-padding[-{!grid_{$g}_panel_padding: default}] {@grid_{$g}_panel_style: |tile-.*} {@grid_{$g}_panel_style}",
            "uk-card-body {@grid_{$g}_panel_style: card-.*} {@grid_{$g}_panel_padding}",
            "uk-margin-remove-first-child" => !in_array($element["grid_{$g}_image_align"],
                    ['left', 'right']) || !($element["grid_{$g}_panel_padding"]),
            // Let images cover the card/tile height if they have different heights
            "uk-flex uk-flex-column{@grid_{$g}_panel_style} {@grid_{$g}_image_align: left|right}",

            // Deprecated remove in v.2.0.0
            "fs-switcher-fieldset-panel fs-switcher-fieldset-panel-$f",
        ],
    ]) : null;

    // Meta
    ${"meta_$f"} = $hasValue($fieldset['meta']) ? $this->el($element["meta_{$f}_element"] ?? 'div', [
        'class' => [
            "fs-switcher__fieldset-meta el-meta--$f",
            "uk-{meta_{$f}_style}",
            "uk-[text-{@meta_{$f}_style: meta}]{meta_{$f}_style}",
            "uk-text-{meta_{$f}_color}",
            "uk-link-{meta_{$f}_hover_style}{@meta_{$f}_hover_style}",
            "uk-margin[-{meta_{$f}_margin}]-top {@!meta_{$f}_margin: remove}",
            "uk-margin-remove-bottom [uk-margin-{meta_{$f}_margin: remove}-top]" => !in_array($element["meta_{$f}_style"],
                    ["", "meta"], true) || $element["meta_{$f}_element"] !== "div",
            "uk-flex-{text_align}{@!text_align: justify}",

            // Deprecated remove in v.2.0.0
            "fs-switcher-meta fs-switcher-meta-$f",
        ],
    ]) : null;

    // Text
    ${"text_$f"} = $hasValue($fieldset['text']) ? $this->el($element["text_{$f}_element"] ?? 'div', [
        'class' => [
            "fs-switcher__fieldset-text el-text--$f",
            "uk-{text_{$f}_style}",
            "uk-text-{text_{$f}_color}",
            "uk-link-{text_{$f}_hover_style}{@text_{$f}_hover_style}",
            "uk-margin[-{text_{$f}_margin}]-top {@!text_{$f}_margin: remove}",
            "uk-margin-remove-bottom [uk-margin-{text_{$f}_margin: remove}-top]" => !in_array($element["text_{$f}_style"],
                    ["", "meta"], true) || $element["text_{$f}_element"] !== "div",
            "uk-flex-{text_align}{@!text_align: justify}",

            // Deprecated remove in v.2.0.0
            "fs-switcher-text fs-switcher-text-$f",
        ],
    ]) : null;

    // Image
    $props["image_$f"] = $props["image_$f"] ?? '';
    if (!empty($fieldset['image'])) {
        $fieldset_image = $this->el('image', [
            'class' => [
                "fs-switcher__fieldset-image el-image--$f",
                "uk-border-{grid_{$g}_image_border}",
                "uk-animation-stroke {@grid_{$g}_image_svg_animate}",
                "uk-text-{grid_{$g}_image_svg_color}" => $element["grid_{$g}_image_svg_inline"] && $this->isImage($props["image_$f"]) === 'svg',
                "uk-inverse-{grid_{$g}_image_text_color}",
                "uk-margin[-{grid_{$g}_image_margin_top}]-top {@!grid_{$g}_image_margin_top: remove}{@grid_{$g}_image_align:bottom}",
                "uk-margin[-{grid_{$g}_image_margin_bottom}]-bottom {@!grid_{$g}_image_margin_bottom: remove}{@grid_{$g}_image_align:top}",

                // Deprecated remove in v.2.0.0
                "fs-switcher-image fs-switcher-image-$f",
            ],
            'src' => $props["image_$f"],
            'alt' => $props["image_{$f}_alt"],
            'title' => $props["image_{$f}_alt"],
            'width' => $element["grid_{$g}_image_width"] ?? 100,
            'height' => $element["grid_{$g}_image_height"],

            //Custom Image Attributes Set
            'loading' => $element["grid_{$g}_image_loading"] ? false : null,
            'fetchpriority' => $element["grid_{$g}_image_fetchpriority"] ? 'high' : null,
            'decoding' => $element["grid_{$g}_image_decoding"] ? 'async' : null,
            'thumbnail' => !$element["grid_{$g}_image_cache_disable"],
            'uk-svg' => $element["grid_{$g}_image_svg_inline"] ? $this->expr([
                'stroke-animation: true;',
            ], $element) : false,
        ]);
        $props["image_$f"] = $fieldset_image($element);
    } elseif (!empty($fieldset['icon'])) {
        $fieldset_icon = $this->el('span', [
            'class' => [
                "fs-switcher__fieldset-icon el-icon--$f",
                "[uk-text-{grid_{$g}_icon_color}]",
                "[{grid_{$g}_image_margin_top}-top {@grid_{$g}_image_align:bottom}]",
                "[{grid_{$g}_image_margin_bottom}-bottom {@grid_{$g}_image_align:top}]",
                'uk-link-heading' => $props["link_$f"],

                // Deprecated remove in v.2.0.0
                "fs-switcher-image fs-switcher-image-$f fs-switcher-icon-$f",
            ],
            'uk-icon' => [
                "icon: {0};" => $props["image_{$f}_icon"],
                "width: {0};" => $element["grid_{$g}_icon_width"] ?: ($element["grid_{$g}_image_width"] ?? 100),
            ],
        ]);
        $props["image_$f"] = $fieldset_icon($element);
    }

    // Fieldset Inner Grid
    ${"fieldset_{$f}_grid"} = $props["image_$f"] && in_array($element["grid_{$g}_image_align"],
        ['left', 'right']) ? $this->el('div', [
        'class' => [
            'fs-switcher__fieldset-grid',
            'uk-child-width-expand',
            "uk-flex-middle {@grid_{$g}_image_vertical_align}{@grid_{$g}_image_align:left|right}",
            "uk-grid-column-{grid_{$g}_image_grid_column_gap}",
            "uk-grid-row-{grid_{$g}_image_grid_row_gap}",
        ],
        'uk-grid' => true,
    ]) : null;

    // Cell Image
    ${"fieldset_{$f}_image_cell"} = ${"fieldset_{$f}_grid"} ? $this->el('div', [
        'class' => [
            "fs-switcher__fieldset-image-cell",
            "uk-width-{grid_{$g}_image_grid_width}[@{grid_{$g}_image_grid_breakpoint}]",
            "uk-flex-last[@{grid_{$g}_image_grid_breakpoint}] {@grid_{$g}_image_align: right}",

            // Deprecated remove in v.2.0.0
            "fs-switcher-cell-image",
        ],
    ]) : null;

    // Cell Content
    ${"fieldset_{$f}_content_cell"} = ${"fieldset_{$f}_grid"} ? $this->el('div', [
        'class' => [
            "fs-switcher__fieldset-content-cell",
            'uk-margin-remove-first-child',

            // Deprecated remove in v.2.0.0
            "fs-switcher-cell-text",
        ],
    ]) : null;

    // Link
    ${"link_$f"} = $fieldset['link'] ? $this->el($props["link_$f"] ? 'a' : 'span', [
        'class' => [
            "fs-switcher__fieldset-link el-link--$f",
            'uk-link-toggle',

            // Deprecated remove in v.2.0.0
            "fs-switcher-link fs-switcher-link-$f",
        ],
        'href' => $props["link_$f"],
        'target' => $this->expr(["_blank {@link_{$f}_target}{@!link_{$f}_toggle}"], $props) ?: false,
        str_contains((string)$props["link_$f"], '#')
            ? ($props["link_{$f}_toggle"] ? 'uk-toggle' : 'uk-scroll')
            : false,
    ]) : null;

    // Limit Output
    if (!empty($element["text_{$f}_limit"]) && !empty($props["text_$f"])) {
        $limit = (int)($element["text_{$f}_limit_length"] ?? 0);
        $plain = strip_tags($props["text_$f"]);
        $props["text_$f"] = ($limit > 0 && mb_strlen($plain, 'UTF-8') > $limit) ? mb_substr($plain, 0, $limit,
                'UTF-8') . '...' : $props["text_$f"];
    }

    ?>

    <?= ${"fieldset_$f"}($element) ?>
    <?= ${"link_$f"} ? ${"link_$f"}($element) : '' ?>
    <?= ${"panel_$f"} ? ${"panel_$f"}($element) : '' ?>

    <?php if (${"fieldset_{$f}_grid"}): ?>
        <?= ${"fieldset_{$f}_grid"}($element) ?>
        <?= ${"fieldset_{$f}_image_cell"}($element, $props["image_$f"]) ?>
        <?= ${"fieldset_{$f}_content_cell"}($element) ?>
    <?php endif ?>

    <?php if ($props["image_$f"] && $element["grid_{$g}_image_align"] === 'top'): ?>
        <div><?= $props["image_$f"] ?></div>
    <?php endif ?>

    <?php if (${"meta_$f"} && $element["meta_{$f}_position"] === 'above-custom-text'): ?>
        <?= ${"meta_$f"}($element, $props["meta_$f"]) ?>
    <?php endif ?>

    <?= ${"text_$f"} ? ${"text_$f"}($element, $props["text_$f"]) : '' ?>

    <?php if (${"meta_$f"} && $element["meta_{$f}_position"] === "below-custom-text"): ?>
        <?= ${"meta_$f"}($element, $props["meta_$f"]) ?>
    <?php endif ?>

    <?php if ($props["image_$f"] && $element["grid_{$g}_image_align"] === 'bottom'): ?>
        <div><?= $props["image_$f"] ?></div>
    <?php endif ?>

    <?= ${"fieldset_{$f}_grid"} ? ${"fieldset_{$f}_content_cell"}->end() . ${"fieldset_{$f}_grid"}->end() : '' ?>

    <?= ${"panel_$f"} ? ${"panel_$f"}->end() : '' ?>
    <?= ${"link_$f"} ? ${"link_$f"}->end() : '' ?>
    <?= ${"fieldset_$f"}->end() ?>

<?php endforeach; ?>