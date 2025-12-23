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

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

// Include the updates.php file to define the $updates array
include_once Path::get('./updates.php', __DIR__);

use FlartStudio\YOOtheme\Switcher\Pro\ItemPropsHelper;

return [
    'transforms' => [
        // Runs for each item to determine if it should be rendered on the website.
        'render' => static function ($node, $params) {
            // --- Initial Setup ---

            // Get properties from the parent element to access its settings (e.g., visibility toggles).
            $element = (array)$params['parent']->props;

            // Load and assign the custom grids helper, which may contain functions or data.
            $props = $node->props;
            $node->helper = include __DIR__ . "/templates/grids/helper.php";

            // --- Conditionally Clear Native Fields ---
            // Based on parent settings, clear the content of native fields to prevent them from rendering.
            foreach (['meta', 'content', 'image', 'link', 'label', 'thumbnail'] as $key) {
                if (empty($element["show_$key"])) {
                    $node->props[$key] = ''; // The field is disabled, so clear its value.
                }
            }

            // --- Define Render Conditions ---

            // Helper function to robustly check if a value exists (treats "0" as a valid value).
            $hasValue = static fn($val): bool => $val !== null && trim((string)$val) !== '';

            // Prepare the image source for potential use in a lightbox link (see: template-link.php).
            $node->props['image_src'] = $node->props['image'] ?? '';

            // Condition 1: The item must have a valid navigation element.
            $has_nav_element = $hasValue($node->props['title'])
                || $hasValue($node->props['label']) || ($element['switcher_style'] === 'thumbnav' // Thumbnails are only valid nav for 'thumbnav' style.
                    && ($node->props['thumbnail'] || $node->props['thumbnail_icon'] || $node->props['image']));

            // Condition 2: The item must have content to display.
            $has_content = ($hasValue($node->props['title']) && $element['show_title'])
                || $hasValue($node->props['meta'])
                || $hasValue($node->props['content'])
                || $node->props['image'] || !empty($node->helper) // Custom grid content exists.
                || ($element['show_sublayout'] // A sublayout can also act as content.
                    && $node->children
                    && in_array($element['sublayout_position'], ['above-content', 'below-content']));

            // The final decision: Render the item only if both conditions are met.
            return $has_nav_element && $has_content;
        },
        // Runs before rendering the element
        'preload' => static fn($node, $params) => ItemPropsHelper::updateProps($node, $params, 'preload'),

        // Executed before saving the element settings in the database.
        'presave' => static fn($node, $params) => ItemPropsHelper::updateProps($node, $params, 'presave'),
    ],
];