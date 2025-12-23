<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use function YOOtheme\app;
use YOOtheme\Builder\ElementTransform;

return [
    'transforms' => [
        'render' => function ($node) {
            /** @var ElementTransform $transform */
            $transform = app(ElementTransform::class);

            $text = $node->props['text'] ?? '';
            $value = $node->props['value'] ?? '';

            $node->props['value'] = $value;
            $node->props['text'] = empty($text) ? $value : $text;

            $transform->customAttributes($node);

            return true;
        },
    ],
];
