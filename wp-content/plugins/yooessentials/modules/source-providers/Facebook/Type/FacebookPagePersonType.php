<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

// https://developers.facebook.com/docs/graph-api/reference/page
class FacebookPagePersonType extends GenericType
{
    public const NAME = 'FacebookPagePerson';
    public const LABEL = 'Page Person';

    public function config(): array
    {
        return [
            'fields' => [
                'birthday' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Birthday',
                        'filters' => ['date'],
                    ],
                ],
                'personal_interests' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Personal Interests',
                        'filters' => ['limit'],
                    ],
                ],
                'affiliation' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Affiliation',
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

    public static function resolve($post): array
    {
        return $post;
    }
}
