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

/** @noinspection DuplicatedCode */

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

// Include the updates.php file to define the $updates array
include_once Path::get('./updates.php', __DIR__);

use FlartStudio\YOOtheme\Switcher\Sl\ElementPropsHelper;

return [
    'transforms' => [
        // Runs when the Switcher Sl is rendered on the website's front end
        'render' => static function ($node) {
            // Enqueue the primary JavaScript asset
            app(Metadata::class)->set('script:fs-switcher', [
                'src' => Path::get('./../assets/js/fs-switcher.min.js?v=1.6.1', __DIR__),
                'defer' => true,
            ]);

            // --- Prepare and Sanitize Child Item Props ---
            foreach ($node->children as $i => $child) {
                // Use a reference to modify the child's props directly for efficiency.
                $item = &$child->props;

                // No label field in Switcher SL; assign title directly.
                $item['nav_title'] = $item['title'];

                $sanitizedSlug = strtr(strtolower(trim(strip_tags($item['tab_link'] ?: $item['nav_title'] ?: "tab-$i"))),
                    [' ' => '-'], // Replace spaces with hyphens.
                );

                $sanitizedSlug = htmlspecialchars($sanitizedSlug, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                // Build the final tab link. Prepend a prefix if the slug starts with a number,
                // as HTML IDs cannot begin with a digit.
                $item['tab_link'] = '#' . (ctype_digit($sanitizedSlug[0] ?? '') ? 'tab-' : '') . $sanitizedSlug;

                // Strip tags and escape special characters to prevent XSS attacks.
                foreach (['id', 'class', 'attributes'] as $key) {
                    $item[$key] = htmlspecialchars(trim(strip_tags((string)($item[$key] ?? ''))),
                        ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }
            }
        },
        // Runs before rendering the element
        'preload' => static fn($node) => ElementPropsHelper::updateProps($node, 'preload'),

        // Executed before saving the element settings in the database.
        'presave' => static fn($node) => ElementPropsHelper::updateProps($node, 'presave'),
    ],
];