<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            $node->props['content'];
        },
    ],
];
