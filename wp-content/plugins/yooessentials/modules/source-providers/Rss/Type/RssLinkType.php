<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class RssLinkType extends GenericType
{
    public const NAME = 'RSSLink';
    public const LABEL = 'Link';

    public function config(): array
    {
        return [
            'fields' => [
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                        'fields' => [],
                    ],
                ],
                'rel' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Rel',
                        'fields' => [],
                    ],
                ],
            ],
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolve($data, $args): array
    {
        $link = $data[$args['header']] ?? [];

        $link['url'] = urldecode($link['url'] ?? ($link['href'] ?? ''));

        return $link;
    }
}
