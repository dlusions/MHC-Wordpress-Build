<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class RssAuthorType extends GenericType
{
    public const NAME = 'RSSAuthor';
    public const LABEL = 'Author';

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                        'fields' => [],
                        'filters' => ['limit'],
                    ],
                ],
                'email' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Email',
                        'fields' => [],
                        'filters' => ['limit'],
                    ],
                ],
                'uri' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Uri',
                        'fields' => [],
                        'filters' => ['limit'],
                    ],
                ],
            ],
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }
}
