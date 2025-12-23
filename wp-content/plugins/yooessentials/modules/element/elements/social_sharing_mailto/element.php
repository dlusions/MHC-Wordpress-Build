<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace YOOtheme;

use YOOtheme\Builder\ElementTransform;

return [
    'transforms' => [
        'render' => function ($node, array $params) {
            /** @var View $view */
            $view = app(View::class);

            /** @var ElementTransform $transform */
            $transform = new ElementTransform($view);

            // set attributes
            $node->attrs += [
                'id' => $node->props['id'] ?? null,
                'title' => $node->props['title'] ?? null,
                'target' => $node->props['target'] ?? null,
                'class' => !empty($node->props['class']) ? [$node->props['class']] : [],
            ];

            // apply attributes transforms
            $transform->customAttributes($node);

            // Don't render element if content fields are empty
            return $node->props['email_subject'] ?? false;
        },
    ],
];
