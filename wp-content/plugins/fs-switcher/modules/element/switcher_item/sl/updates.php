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

class ItemPropsHelper
{
    private static ?array $updates = null;

    /**
     * Update node properties based on version migrations.
     *
     * @param object $node The node object to update.
     * @param string $event The current event (e.g., 'presave', 'init').
     *
     * @return void
     * @since 1.6.0
     */
    public static function updateProps(object $node, string $event): void
    {
        $version = $node->props['_fs_props_version'] ?? '0.0.0';

        self::$updates ??= [
            // Migration 1.6.0 - Rename custom field props and clean obsolete props
            '1.6.0' => static function ($node, string $event, string &$version): void {
                // Main properties
                self::updateNodeProps($node, [
                    'nav_link' => 'tab_link',
                    'hover_image' => 'thumbnail_hover',
                    'icon' => 'thumbnail_icon',
                ]);

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