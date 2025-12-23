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

use FlartStudio\YOOtheme\Switcher\Sl\ItemPropsHelper;

return [
    'transforms' => [
        'render' => static function ($node, $params) {
            // Don't render the element if content fields are empty
            return $node->props['title'] || (($node->props['image'] || $node->props['thumbnail_icon']) && $params['parent']->props['switcher_style'] === 'thumbnav');
        },
        // Runs before rendering the element
        'preload' => static fn($node) => ItemPropsHelper::updateProps($node, 'preload'),

        // Executed before saving the element settings in the database.
        'presave' => static fn($node) => ItemPropsHelper::updateProps($node, 'presave'),
    ],
];