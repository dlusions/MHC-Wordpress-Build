<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class RssEnclosureType extends GenericType
{
    public const NAME = 'RSSEnclosure';
    public const LABEL = 'Media (Enclosure)';

    public function config(): array
    {
        return [
            'fields' => [
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Url',
                        'fields' => [],
                    ],
                ],
                'length' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Length',
                        'fields' => [],
                    ],
                ],
                'type' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Type',
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

    public static function resolve(array $data, array $args = []): array
    {
        $link = $data[$args['header']] ?? [];

        $link['uri'] = urldecode($link['uri'] ?? ($link['url'] ?? ''));

        return $link;
    }
}
