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

class ItemPropsHelper
{
    private static ?array $updates = null;

    /**
     * Update node properties based on version migrations.
     *
     * @param object $node The node object to update.
     * @param array $params Parameters containing parent element context.
     * @param string $event The current event (e.g., 'presave', 'init').
     *
     * @return void
     * @since 1.5.0
     */
    public static function updateProps(object $node, array $params, string $event): void
    {
        $version = $node->props['_fs_props_version'] ?? '0.0.0';
        $element = $params['parent']?->props ?? [];

        self::$updates ??= [
            '1.6.0' => static function (object $node, string $event, string &$version): void {
                // Remove obsolete props
                unset($node->props['_props_version']);

                // Bump only on presave
                $event === 'presave' && $version = '1.6.0';
            },
            // Migration 1.5.0 - Rename custom field props and clean obsolete props
            '1.5.0' => static function ($node, string $event, string &$version, $element): void {
                // Main properties
                self::updateNodeProps($node, [
                    'link_item_target' => 'link_target',
                    'link_item_toggle' => 'link_toggle',
                    'link_item_toggle_modal_integration' => 'link_toggle_modal_integration',
                    'link_item_nofollow' => 'link_rel_nofollow',
                    'link_item_noopener' => 'link_rel_noopener',
                    'link_item_noreferrer' => 'link_rel_noreferrer',
                    'link_item_prefetch' => 'link_rel_prefetch',
                    'image_attrs_tag' => 'image_attributes',
                    'link_attrs_tag' => 'link_attributes',
                    'item_id' => 'id',
                    'item_class' => 'class',
                    'item_attrs_tag' => 'attributes',
                ]);

                // Remove obsolete main properties
                unset(
                    $node->props['show_custom_settings'],
                    $node->props['show_custom_fields'],
                );

                // Grid properties
                foreach (range(1, (int)($element['_grids_count'] ?? 0)) as $g) {
                    // Pass 'false' as the 3rd argument because Grid props are static only
                    self::updateNodeProps($node, [
                        "grid_{$g}_custom_item_position" => "grid_{$g}_position",
                        "grid_{$g}_custom_panel_style" => "grid_{$g}_panel_style",
                        "grid_{$g}_custom_item_visibility" => "grid_{$g}_visibility",
                        "item_disable_custom_grid_$g" => "hide_grid_$g",
                    ], false);

                    // Remove obsolete grid props
                    unset(
                        $node->props["grid_{$g}_custom_item_column_gap"],
                        $node->props["grid_{$g}_custom_item_row_gap"],
                        $node->props["grid_{$g}_custom_item_divider_top"],
                        $node->props["grid_{$g}_custom_item_divider"],
                        $node->props["grid_{$g}_custom_item_column_align"],
                        $node->props["grid_{$g}_custom_item_row_align"],
                        $node->props["grid_{$g}_custom_item_grid_match"],
                        $node->props["grid_{$g}_custom_item_text_align"],
                        $node->props["grid_{$g}_custom_item_text_align_breakpoint"],
                        $node->props["grid_{$g}_custom_item_text_align_fallback"],
                        $node->props["grid_{$g}_custom_item_margin"],
                        $node->props["grid_{$g}_custom_item_default"],
                        $node->props["grid_{$g}_custom_item_small"],
                        $node->props["grid_{$g}_custom_item_medium"],
                        $node->props["grid_{$g}_custom_item_large"],
                        $node->props["grid_{$g}_custom_item_xlarge"],
                        $node->props["grid_{$g}_custom_image_width"],
                        $node->props["grid_{$g}_custom_image_height"],
                        $node->props["grid_{$g}_custom_image_border"],
                        $node->props["grid_{$g}_custom_icon_width"],
                        $node->props["grid_{$g}_custom_icon_color"],
                        $node->props["grid_{$g}_custom_image_align"],
                        $node->props["grid_{$g}_custom_image_grid_width"],
                        $node->props["grid_{$g}_custom_image_grid_column_gap"],
                        $node->props["grid_{$g}_custom_image_grid_row_gap"],
                        $node->props["grid_{$g}_custom_image_grid_breakpoint"],
                        $node->props["grid_{$g}_custom_image_vertical_align"],
                        $node->props["grid_{$g}_custom_image_margin_top"],
                        $node->props["grid_{$g}_custom_image_margin_bottom"],
                        $node->props["grid_{$g}_custom_image_svg_inline"],
                        $node->props["grid_{$g}_custom_image_svg_animate"],
                        $node->props["grid_{$g}_custom_image_svg_color"],
                        $node->props["grid_{$g}_custom_item_slider"],
                    );
                }
                // Fieldset properties
                foreach (range(1, (int)($element['_fieldsets_count'] ?? 0)) as $f) {
                    self::updateNodeProps($node, [
                        "custom_text_$f" => "text_$f",
                        "custom_meta_$f" => "meta_$f",
                        "custom_image_$f" => "image_$f",
                        "custom_image_{$f}_alt" => "image_{$f}_alt",
                        "custom_image_{$f}_icon" => "image_{$f}_icon",
                        "custom_link_$f" => "link_$f",
                        "custom_link_{$f}_item_target" => "link_{$f}_target",
                        "custom_link_{$f}_toggle" => "link_{$f}_toggle",
                        "custom_{$f}_grid_item" => "fieldset_{$f}_target",
                        "custom_{$f}_visibility_item" => "fieldset_{$f}_visibility",
                        "custom_{$f}_mixed_width_item_default" => "fieldset_{$f}_mixed_width_default",
                        "custom_{$f}_mixed_width_item_small" => "fieldset_{$f}_mixed_width_small",
                        "custom_{$f}_mixed_width_item_medium" => "fieldset_{$f}_mixed_width_medium",
                        "custom_{$f}_mixed_width_item_large" => "fieldset_{$f}_mixed_width_large",
                        "custom_{$f}_mixed_width_item_xlarge" => "fieldset_{$f}_mixed_width_xlarge",
                    ]);
                    // Remove obsolete fieldset props
                    unset($node->props["custom_{$f}_meta_grid"]);
                }

                // Bump only on presave
                $event === 'presave' && $version = '1.5.0';
            },
        ];
        // Sort by version number ascending
        $updates = self::$updates;
        uksort($updates, 'version_compare');

        // Apply sequentially
        foreach ($updates as $updateVersion => $callback) {
            if (version_compare($version, $updateVersion, '<')) {
                $callback($node, $event, $version, $element);
            }
        }

        // Store final props version
        $node->props['_fs_props_version'] = $version;
    }

    /**
     * Update static props and dynamic content sources.
     *
     * @param object $node The node object.
     * @param array<string, string> $mappings Key mappings [old_key => new_key].
     * @param bool $includeDynamic Whether to update dynamic sources.
     *
     * @return void
     * @since 1.5.0
     */
    private static function updateNodeProps(object $node, array $mappings, bool $includeDynamic = true): void
    {
        $node->props = Arr::updateKeys($node->props, $mappings);

        if ($includeDynamic) {
            self::updateSourceProps($node, 'source', $mappings);
            self::updateSourceProps($node, 'source_extended', $mappings);
        }
    }

    /**
     * Update keys inside a dynamic source property.
     * Handles both object and array formats from YOOtheme Pro.
     *
     * @param object $node The node object.
     * @param string $property Source property name ('source'|'source_extended').
     * @param array<string, string> $mappings Key mappings [old_key => new_key].
     *
     * @return void
     * @since 1.5.0
     */
    private static function updateSourceProps(object $node, string $property, array $mappings): void
    {
        $source = $node->{$property} ?? null;

        if (!$source) {
            return;
        }

        $isObject = is_object($source);
        $props = $isObject ? ($source->props ?? null) : ($source['props'] ?? null);

        if (!$props || empty($propsArray = (array)$props)) {
            return;
        }

        $updated = Arr::updateKeys($propsArray, $mappings);

        if ($isObject) {
            $source->props = (object)$updated;
        } else {
            $source['props'] = $updated;
            $node->{$property} = $source;
        }
    }
}