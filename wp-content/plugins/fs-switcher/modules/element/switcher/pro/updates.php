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

/** @noinspection AutoloadingIssuesInspection, DuplicatedCode */

namespace FlartStudio\YOOtheme\Switcher\Pro;

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Arr;
use YOOtheme\Application;
use YOOtheme\Config;

class ElementPropsHelper
{
    private static ?array $updates = null;

    /**
     * Update node properties based on version migrations
     *
     * @param object $node The node to update
     * @param string $event The event type (presave, etc.)
     * @return void
     * @since 1.5.0
     */
    public static function updateProps(object $node, string $event): void
    {
        $version = $node->props['_fs_props_version'] ?? '0.0.0';
        self::$updates ??= [
            '1.6.0' => static function (object $node, string $event, string &$version): void {
                // Props
                Arr::updateKeys($node->props, [
                    'image_attr_loading' => 'image_loading',
                    'image_attr_fetchpriority' => 'image_fetchpriority',
                    'image_attr_decoding' => 'image_decoding',
                    'image_thumbnails_disable' => 'image_cache_disable',
                ]);

                // Grids
                foreach (range(1, (int)$node->props['_grids_count']) as $g) {
                    Arr::updateKeys($node->props, [
                        "grid_{$g}_image_thumbnail" => "grid_{$g}_cache_disable",
                    ]);
                }

                // Remove obsolete props
                unset($node->props['_props_version']);

                // Bump only on presave
                $event === 'presave' && $version = '1.6.0';
            },
            '1.5.0' => static function (object $node, string $event, string &$version): void {
                // YOOtheme version detection
                $theme_version = Application::getInstance()->get(Config::class)?->get('theme.version');
                $node->props[version_compare($theme_version, '5.0', '<') ? '_yootheme_v4' : '_yootheme_v5'] = true;

                if (Arr::get($node->props, 'margin')) {
                    $node->props['margin_top'] = Arr::get($node->props, 'margin_remove_top') === true ? 'remove' :
                        Arr::get($node->props, 'margin');
                    $node->props['margin_bottom'] = Arr::get($node->props, 'margin_remove_bottom') === true ? 'remove' :
                        Arr::get($node->props, 'margin');
                    unset($node->props['margin'], $node->props['margin_remove_top'], $node->props['margin_remove_bottom']);
                }

                // Props
                Arr::updateKeys($node->props, [
                    'custom_grids' => '_grids_count',
                    'custom_field_sets' => '_fieldsets_count',
                    'switcher_disable_touch' => 'switcher_swipe_disable',
                    'switcher_autoplay_progress_color' => 'switcher_autoplay_progress_fill',
                    'switcher_autoplay_progress_container_color' => 'switcher_autoplay_progress_track',
                    'use_custom_fields' => 'show_fieldsets',
                    'advanced_enable_mixed_width' => 'enable_fieldsets_mixed_width',
                    'advanced_grid' => 'show_multiple_grids',
                    'advanced_enable_grids_highlight' => '_grids_helper',
                    'nav' => 'slider_dotnav',
                    'nav_align' => 'slider_dotnav_align',
                    'nav_margin' => 'slider_dotnav_margin',
                    'nav_breakpoint' => 'slider_dotnav_breakpoint',
                    'nav_color' => 'slider_dotnav_color',
                    'slidenav' => 'slider_slidenav',
                    'slidenav_hover' => 'slider_slidenav_hover',
                    'slidenav_large' => 'slider_slidenav_large',
                    'slidenav_margin' => 'slider_slidenav_margin',
                    'slidenav_breakpoint' => 'slider_slidenav_breakpoint',
                    'slidenav_color' => 'slider_slidenav_color',
                    'sublayout_modal_wrap' => 'sublayout_modal_group',
                    'sublayout_modal_wrap_custom' => 'sublayout_modal_group_custom',
                ]);
                // Init default values for panel props
                $node->props['slider_dotnav'] ??= 'dotnav';
                $node->props['slider_dotnav_align'] ??= 'center';
                $node->props['slider_slidenav'] ??= 'default';
                $node->props['slider_slidenav_breakpoint'] ??= 'l';

                // Remove obsolete props
                unset(
                    $node->props['advanced_enable_grids_empty_notification'],
                    $node->props['advanced_enable_grids_empty_disable'],
                );

                // Grids
                foreach (range(1, (int)$node->props['_grids_count']) as $g) {
                    Arr::updateKeys($node->props, [
                        "advanced_enable_grid_{$g}_custom" => "show_grid_$g",
                        "grid_{$g}_custom_position" => "grid_{$g}_position",
                        "grid_{$g}_custom_column_gap" => "grid_{$g}_column_gap",
                        "grid_{$g}_custom_row_gap" => "grid_{$g}_row_gap",
                        "grid_{$g}_custom_divider_top" => "grid_{$g}_divider_top",
                        "grid_{$g}_custom_divider" => "grid_{$g}_divider",
                        "grid_{$g}_custom_column_align" => "grid_{$g}_column_align",
                        "grid_{$g}_custom_row_align" => "grid_{$g}_row_align",
                        "grid_{$g}_custom_text_align" => "grid_{$g}_text_align",
                        "grid_{$g}_custom_text_align_breakpoint" => "grid_{$g}_text_align_breakpoint",
                        "grid_{$g}_custom_text_align_fallback" => "grid_{$g}_text_align_fallback",
                        "grid_{$g}_custom_slider" => "grid_{$g}_slider",
                        "grid_{$g}_custom_margin" => "grid_{$g}_margin",
                        "grid_{$g}_custom_visibility" => "grid_{$g}_visibility",
                        "grid_{$g}_custom_default" => "grid_{$g}_default",
                        "grid_{$g}_custom_small" => "grid_{$g}_small",
                        "grid_{$g}_custom_medium" => "grid_{$g}_medium",
                        "grid_{$g}_custom_large" => "grid_{$g}_large",
                        "grid_{$g}_custom_xlarge" => "grid_{$g}_xlarge",
                        "grid_{$g}_custom_panel_style" => "grid_{$g}_panel_style",
                        "grid_{$g}_custom_panel_card_offset" => "grid_{$g}_panel_card_offset",
                        "grid_{$g}_custom_panel_padding" => "grid_{$g}_panel_padding",
                        "grid_{$g}_custom_image_attr_loading" => "grid_{$g}_image_loading",
                        "grid_{$g}_custom_image_attr_fetchpriority" => "grid_{$g}_image_fetchpriority",
                        "grid_{$g}_custom_image_attr_decoding" => "grid_{$g}_image_decoding",
                        "grid_{$g}_custom_image_attr_thumbnails" => "grid_{$g}_image_thumbnail",
                        "grid_{$g}_custom_image_width" => "grid_{$g}_image_width",
                        "grid_{$g}_custom_image_height" => "grid_{$g}_image_height",
                        "grid_{$g}_custom_image_border" => "grid_{$g}_image_border",
                        "grid_{$g}_custom_icon_width" => "grid_{$g}_icon_width",
                        "grid_{$g}_custom_icon_color" => "grid_{$g}_icon_color",
                        "grid_{$g}_custom_image_align" => "grid_{$g}_image_align",
                        "grid_{$g}_custom_image_grid_width" => "grid_{$g}_image_grid_width",
                        "grid_{$g}_custom_image_grid_column_gap" => "grid_{$g}_image_grid_column_gap",
                        "grid_{$g}_custom_image_grid_row_gap" => "grid_{$g}_image_grid_row_gap",
                        "grid_{$g}_custom_image_grid_breakpoint" => "grid_{$g}_image_grid_breakpoint",
                        "grid_{$g}_custom_image_vertical_align" => "grid_{$g}_image_vertical_align",
                        "grid_{$g}_custom_image_margin_top" => "grid_{$g}_image_margin_top",
                        "grid_{$g}_custom_image_margin_bottom" => "grid_{$g}_image_margin_bottom",
                        "grid_{$g}_custom_image_svg_inline" => "grid_{$g}_image_svg_inline",
                        "grid_{$g}_custom_image_svg_animate" => "grid_{$g}_image_svg_animate",
                        "grid_{$g}_custom_image_svg_color" => "grid_{$g}_image_svg_color",
                    ]);
                    // Init default values for panel props
                    $node->props["grid_{$g}_position"] ??= 'below-content';
                    $node->props["grid_{$g}_default"] ??= '1-1';
                    $node->props["grid_{$g}_image_align"] ??= 'top';
                    $node->props["grid_{$g}_image_grid_width"] ??= '1-2';
                    $node->props["grid_{$g}_image_grid_breakpoint"] ??= 'm';

                    // Breakpoints
                    foreach (['text_align', 'image_grid'] as $type) {
                        $key = "grid_{$g}_{$type}_breakpoint";
                        if (!empty($node->props[$key]) && $node->props[$key][0] === '@') {
                            $node->props[$key] = substr($node->props[$key], 1);
                        }
                    }
                    // Image Margins
                    foreach (['top', 'bottom'] as $pos) {
                        $key = "grid_{$g}_margin_$pos";
                        if (!empty($node->props[$key]) && str_starts_with($node->props[$key], 'uk-margin')) {
                            $node->props[$key] = trim(substr($node->props[$key], 9), '-');
                        }
                    }
                    // Normalize invalid HTML element types
                    foreach (['title', 'meta', 'content'] as $el) {
                        $key = "{$el}_element";
                        if (isset($node->props[$key]) && in_array($node->props[$key], ['span', 'object'], true)) {
                            $node->props[$key] = 'div';
                        }
                    }

                    // Remove obsolete props
                    unset($node->props["grid_{$g}_custom_grid_match"]);
                }

                // Fieldsets
                foreach (range(1, (int)$node->props['_fieldsets_count']) as $f) {
                    Arr::updateKeys($node->props, [
                        "show_custom_$f" => "show_fieldset_$f",
                        "show_custom_text_$f" => "show_text_$f",
                        "show_custom_meta_$f" => "show_meta_$f",
                        "show_custom_image_$f" => "show_image_$f",
                        "show_custom_link_$f" => "show_link_$f",
                        "custom_{$f}_grid" => "fieldset_{$f}_target",
                        "custom_{$f}_visibility" => "fieldset_{$f}_visibility",
                        "custom_{$f}_limit" => "text_{$f}_limit",
                        "custom_{$f}_limit_length" => "text_{$f}_limit_length",
                        "custom_{$f}_style" => "text_{$f}_style",
                        "custom_{$f}_hover_style" => "text_{$f}_hover_style",
                        "custom_{$f}_color" => "text_{$f}_color",
                        "custom_{$f}_element" => "text_{$f}_element",
                        "custom_{$f}_margin" => "text_{$f}_margin",
                        "custom_{$f}_meta_position" => "meta_{$f}_position",
                        "custom_{$f}_meta_style" => "meta_{$f}_style",
                        "custom_{$f}_meta_hover_style" => "meta_{$f}_hover_style",
                        "custom_{$f}_meta_color" => "meta_{$f}_color",
                        "custom_{$f}_meta_element" => "meta_{$f}_element",
                        "custom_{$f}_meta_margin" => "meta_{$f}_margin",
                        "custom_{$f}_mixed_width_default" => "fieldset_{$f}_mixed_width_default",
                        "custom_{$f}_mixed_width_small" => "fieldset_{$f}_mixed_width_small",
                        "custom_{$f}_mixed_width_medium" => "fieldset_{$f}_mixed_width_medium",
                        "custom_{$f}_mixed_width_large" => "fieldset_{$f}_mixed_width_large",
                        "custom_{$f}_mixed_width_xlarge" => "fieldset_{$f}_mixed_width_xlarge",
                    ]);
                    // Init default values for panel props
                    $node->props["fieldset_{$f}_target"] ??= '1';
                    $node->props["show_text_$f"] ??= true;
                    $node->props["show_meta_$f"] ??= true;
                    $node->props["show_image_$f"] ??= true;
                    $node->props["show_link_$f"] ??= true;
                    $node->props["text_{$f}_element"] ??= 'div';
                    $node->props["meta_{$f}_element"] ??= 'div';
                    $node->props["meta_{$f}_position"] ??= 'above-custom-text';
                }
                // Bump only on presave
                $event === 'presave' && $version = '1.5.0';
            },
            '1.0.0' => static function (object $node, string $event, string &$version): void {
                // Props
                Arr::updateKeys($node->props, [
                    'show_custom_settings' => '_grids_navigator',
                    'show_element_settings' => '_settings_navigator',
                ]);
                // Bump only on presave
                $event === 'presave' && $version = '1.0.0';
            },
        ];
        // Set properties for custom grid rendering logic.
        $node->props['_grids_count'] = is_numeric($node->props['_grids_count'] ?? null) ?
            (int)max(1, min(6, (int)$node->props['_grids_count'])) : 1;
        $node->props['_fieldsets_count'] = is_numeric($node->props['_fieldsets_count'] ?? null) ?
            (int)max(1, min(20, (int)$node->props['_fieldsets_count'])) : 1;

        // Sort by version number ascending
        $updates = self::$updates;
        uksort($updates, 'version_compare');

        // Apply sequentially
        foreach ($updates as $updateVersion => $callback) {
            if (version_compare($version, $updateVersion, '<')) {
                $callback($node, $event, $version);
            }
        }

        // Disable unused fieldset rendering (fieldset range slider)
        $max = $node->props['_fieldsets_count'];
        while ($max > 0 && empty($node->props["show_fieldset_$max"])) {
            $max--;
        }
        $node->props['_fieldsets_count'] = max($max, 1);
        $node->props['show_fieldsets'] = (bool)$max;

        // Disable unused grids rendering (grids range slider)
        $max = $node->props['_grids_count'];
        while ($max > 0 && empty($node->props["show_grid_$max"])) {
            $max--;
        }
        $node->props['_grids_count'] = max($max, 1);
        $node->props['show_multiple_grids'] = $max === 1 ? false : $node->props['show_multiple_grids'];

        // Reset navigators dropdown
        $node->props['_settings_navigator'] = $node->props['_grids_navigator'] = 'all';

        // Store final props version
        $node->props['_fs_props_version'] = $version;
    }
}