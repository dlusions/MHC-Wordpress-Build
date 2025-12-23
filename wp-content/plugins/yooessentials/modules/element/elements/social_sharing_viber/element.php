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
            /** @var Config $config */
            $config = app(Config::class);

            /** @var View $view */
            $view = app(View::class);

            /** @var ElementTransform $transform */
            $transform = new ElementTransform($view);

            /** @var Metadata $metadata */
            $metadata = app(Metadata::class);

            $node->title = $node->props['title'] ?? null;

            if (empty($node->props['icon'])) {
                $iconKey = 'ye--social-sharing-viber';
                $icon = File::getContents(__DIR__ . '/icon.svg');
                $metadata->set(
                    'script:yooessentials-social-sharing-viber',
                    sprintf('UIkit.icon.add(%s)', json_encode([$iconKey => $icon]))
                );

                $node->props['icon'] = $iconKey;
            }

            // set attributes
            $node->attrs += [
                'id' => $node->props['id'] ?? null,
                'class' => !empty($node->props['class']) ? [$node->props['class']] : [],
            ];

            // apply attributes transforms
            $transform->customAttributes($node);

            $url = $node->props['shared_url'] ?? '';
            $text = $node->props['shared_text'] ?? '';

            if (empty($url)) {
                $url = $config->get('req.href');
            }

            if (empty($text)) {
                $text = '{URL}';
            }

            $text = str_replace('{URL}', $url, $text);

            $node->props['shared_text'] = urlencode($text);

            // Don't render element if content fields are empty
            return $node->props['shared_text'] ?? false;
        },
    ],
];
