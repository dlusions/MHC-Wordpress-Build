<?php /**
 * @package     [FS] Switcher SL for YOOtheme Pro
 * @subpackage  fs-switcher
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/switcher
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection AutoloadingIssuesInspection, DuplicatedCode */

namespace FlartStudio\YOOtheme\Switcher\Sl;

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
     * @since 1.6.0
     */
    public static function updateProps(object $node, string $event): void
    {
        $version = $node->props['_fs_props_version'] ?? '0.0.0';
        self::$updates ??= [
            '1.6.0' => static function (object $node, string $event, string &$version): void {
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
                    'nav_disable_touch' => 'switcher_swipe_disable',
                    'switcher_autoplay_progress_color' => 'switcher_autoplay_progress_fill',
                    'switcher_autoplay_progress_background_color' => 'switcher_autoplay_progress_track',
                    'nav' => 'switcher_style',
                    'nav_mode' => 'switcher_mode',
                    'nav_vertical_align' => 'switcher_vertical_align',
                    'nav_style_primary' => 'switcher_style_primary',
                    'nav_grid_divider' => 'switcher_grid_divider',
                    'nav_position' => 'switcher_position',
                    'nav_grid_width' => 'switcher_grid_width',
                    'nav_grid_column_gap' => 'switcher_grid_column_gap',
                    'nav_grid_row_gap' => 'switcher_grid_row_gap',
                    'nav_grid_breakpoint' => 'switcher_grid_breakpoint',
                    'nav_align' => 'switcher_align',
                    'nav_position_sticky' => 'position_sticky',
                    'nav_position_sticky_offset' => 'position_sticky_offset',
                    'nav_position_sticky_breakpoint' => 'position_sticky_breakpoint',
                    'nav_margin' => 'switcher_margin',
                    'nav_accordion_multiple' => 'switcher_accordion_multiple',
                    'nav_accordion_collapsible' => 'switcher_accordion_collapsible',
                    'show_thumbnav_image' => 'switcher_accordion_thumbnail',
                    'thumbnav_width' => 'switcher_thumbnail_width',
                    'thumbnav_height' => 'switcher_thumbnail_height',
                    'thumbnav_icon_width' => 'switcher_thumbnail_icon_width',
                    'thumbnav_loading' => 'switcher_thumbnail_loading',
                    'thumbnav_grid_column_gap' => 'switcher_thumbnail_grid_column_gap',
                    'thumbnav_icon_color' => 'switcher_thumbnail_icon_color',
                    'thumbnav_border' => 'switcher_thumbnail_border',
                    'thumbnav_svg_inline' => 'switcher_thumbnail_svg_inline',
                    'thumbnav_svg_color' => 'switcher_thumbnail_svg_color',
                    'show_thumbnav_hover_image' => 'switcher_thumbnail_hover',
                    'show_thumbnav_label' => 'switcher_thumbnail_label',
                    'thumbnav_label_visibility' => 'switcher_thumbnail_label_visibility',
                ]);

                if (Arr::get($node->props, 'switcher_mode') === 'click') {
                    $node->props['switcher_mode'] = '';
                }
                if (Arr::get($node->props, 'switcher_autoplay_progress_position') === '') {
                    $node->props['switcher_autoplay_progress_position'] = 'item-top';
                }

                // Remove obsolete props
                unset(
                    $node->props['item_maxwidth'],
                    $node->props['show_thumbnav_hover_image_on_active'],
                    $node->props['thumbnav_align'],
                    $node->props['thumbnav_grid_width'],
                    $node->props['thumbnav_grid_row_gap'],
                    $node->props['thumbnav_grid_breakpoint'],
                    $node->props['thumbnav_vertical_align'],
                    $node->props['thumbnav_margin'],
                    $node->props['thumbnav_visibility'],
                    $node->props['thumbnav_label_align'],
                    $node->props['thumbnav_label_margin'],
                    $node->props['grid_column_gap'],
                    $node->props['grid_row_gap'],
                    $node->props['grid_horizontal_align'],
                    $node->props['grid_default'],
                    $node->props['grid_small'],
                    $node->props['grid_medium'],
                    $node->props['grid_large'],
                    $node->props['grid_xlarge'],
                );

                // Bump only on presave
                $event === 'presave' && $version = '1.6.0';
            },
        ];
        // Sort by version number ascending
        $updates = self::$updates;
        uksort($updates, 'version_compare');

        // Apply sequentially
        foreach ($updates as $updateVersion => $callback) {
            if (version_compare($version, $updateVersion, '<')) {
                $callback($node, $event, $version);
            }
        }

        // Store final props version
        $node->props['_fs_props_version'] = $version;
    }
}