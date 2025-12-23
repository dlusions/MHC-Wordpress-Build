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

            /** @var Metadata $metadata */
            $metadata = app(Metadata::class);

            /** @var ElementTransform $transform */
            $transform = new ElementTransform($view);

            $metadata->set('script:yooessentials-social-sharing-network', [
                'src' => '~yooessentials_url/modules/element/elements/social_sharing_item/assets/asset.js',
                'defer' => true,
            ]);

            $networks = [
                'bluesky' => 'https://bsky.app/intent/compose?text={TEXT}%0A{URL}',
                'facebook' => 'https://www.facebook.com/sharer/sharer.php?u={URL}',
                'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url={URL}',
                'mastodon' => 'https://mastodon.social/share?url={URL}&text={TEXT}',
                'pinterest' => 'http://pinterest.com/pin/create/button/?url={URL}',
                'telegram' => 'https://t.me/share/url?url={URL}&text={TEXT}',
                'whatsapp' => 'https://api.whatsapp.com/send?text={TEXT}',
                'x' => 'https://x.com/intent/post?url={URL}&text={TEXT}',
                'xing' => 'https://www.xing.com/spi/shares/new?url={URL}',
                'custom' => $node->props['custom_link'] ?? '',
            ];

            $url = $node->props['shared_url'] ?? '';
            $text = $node->props['shared_text'] ?? '';
            $network = $node->props['network'] ?? '';
            $networkUrl = $networks[$network] ?? '';

            if (empty($url)) {
                $url = $config->get('req.href');
            }

            if ($network === 'whatsapp' && empty($text)) {
                $text = '{URL}';
            }

            $text = str_replace('{URL}', $url, $text);
            $networkUrl = str_replace('{URL}', urlencode($url), $networkUrl);
            $networkUrl = str_replace('{TEXT}', urlencode($text), $networkUrl);

            $node->props['link'] = $networkUrl;

            if ($node->props['link_target'] === 'popup') {
                $node->popup = json_encode([
                    'width' => $node->props['link_target_width'] ?: 600,
                    'height' => $node->props['link_target_height'] ?: 600,
                ]);
            }

            // set attributes
            $node->attrs += [
                'id' => $node->props['id'] ?? null,
                'class' => !empty($node->props['class']) ? [$node->props['class']] : [],
            ];

            // apply attributes transforms
            $transform->customAttributes($node);

            // Don't render element if content fields are empty
            return $node->props['link'];
        },
    ],
];
