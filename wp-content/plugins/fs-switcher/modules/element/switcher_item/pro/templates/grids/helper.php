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
/** @var $__dir */

// Exit if custom fields are not enabled
if (!$element['show_fieldsets']) {
    return;
}

$helper = [];
$grids = $element['show_multiple_grids']
    ? range(1, (int)$element['_grids_count'])
    : [1]; // always grid 1 if not advanced

foreach ($grids as $g) {
    // Skip disabled advanced grids or user-disabled ones
    if (empty($element["show_grid_$g"]) || ($props["hide_grid_$g"] ?? false)) {
        continue;
    }

    // Override grid position only if the custom position is not empty
    if (!empty($props["grid_{$g}_position"])) {
        $element["grid_{$g}_position"] = $props["grid_{$g}_position"];
    }

    // Get the position for this grid
    $position = $element["grid_{$g}_position"];

    // If not advanced grid, force all fields to this grid
    if (!$element['show_multiple_grids']) {
        foreach (range(1, (int)$element['_fieldsets_count']) as $f) {
            $element["fieldset_{$f}_target"] = 1;
        }
    }

    $gridHasContent = false;
    $fieldsets = [];

    foreach (range(1, (int)$element['_fieldsets_count']) as $f) {
        //Override Target Grid
        $element["fieldset_{$f}_target"] = $props["fieldset_{$f}_target"] ?: $element["fieldset_{$f}_target"];

        // Skip fieldsets not assigned to this grid
        if (empty($element["show_fieldset_$f"]) || ((int)$element["fieldset_{$f}_target"] !== $g)) {
            continue;
        }

        $fieldTypes = ['image', 'text', 'meta', 'link'];
        $fieldsetHasContent = false;
        $fieldsets[$f] = [];

        foreach ($fieldTypes as $type) {
            $showKey = "show_{$type}_$f";

            if (empty($element[$showKey])) {
                $fieldsets[$f][$type] = '';
                continue;
            }

            $contentKey = "{$type}_$f";
            $actualType = $type;

            // Check if we should use icon instead of image
            if ($type === 'image' && empty($props[$contentKey]) && !empty($props["image_{$f}_icon"])) {
                $contentKey = "image_{$f}_icon";
                $actualType = 'icon';
            }

            $value = $props[$contentKey] ?? '';
            $fieldsets[$f][$actualType] = $value;

            // Any non-empty value marks fieldset as having content ("0" is valid for text/meta)
            if ($value !== null && trim((string)$value) !== '') {
                $fieldsetHasContent = true;
            }
        }

        // If a fieldset has no content, remove it and disable it
        if (!$fieldsetHasContent) {
            $element["show_fieldset_$f"] = false;
            unset($fieldsets[$f]);
        } else {
            $gridHasContent = true;
        }
    }

    // Only add grid to the helper if it has content
    if ($gridHasContent) {
        // Initialize a position array if not exists
        if (!isset($helper[$position])) {
            $helper[$position] = [];
        }
        $helper[$position][$g] = $fieldsets;
    } else {
        // If no content in this grid, disable the grid (for advanced grids)
        if ($element['show_multiple_grids']) {
            $element["show_grid_$g"] = false;
        }
        // If the position is now empty, remove it
        if (isset($helper[$position]) && empty($helper[$position])) {
            unset($helper[$position]);
        }
    }
}

return $helper;