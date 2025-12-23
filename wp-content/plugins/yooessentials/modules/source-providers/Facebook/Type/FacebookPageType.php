<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

// https://developers.facebook.com/docs/graph-api/reference/page
class FacebookPageType extends GenericType
{
    public const NAME = 'FacebookPage';
    public const LABEL = 'Page';

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                ],
                'username' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Username',
                    ],
                ],
                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Page URL',
                    ],
                ],
                'category' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Category',
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'description_html' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description HTML',
                    ],
                ],
                'about' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'About',
                        'filters' => ['limit'],
                    ],
                ],
                'general_info' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'General Info',
                        'filters' => ['limit'],
                    ],
                ],
                'website' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Website URL',
                    ],
                ],
                'whatsapp_number' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'WhatsApp Number',
                    ],
                ],
                'rating_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Rating',
                    ],
                ],
                'followers_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Followers',
                    ],
                ],
                'talking_about_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Mentions',
                    ],
                ],
                'overall_star_rating' => [
                    'type' => 'Float',
                    'metadata' => [
                        'label' => 'Overall Star Rating',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],

                // 'full_picture' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Picture Full Size URL',
                //     ],
                //     'extensions' => [
                //         'call' => __CLASS__ . '::fullPicture',
                //     ],
                // ],

                'person' => [
                    'type' => FacebookPagePersonType::NAME,
                    'metadata' => [
                        'label' => 'Person',
                    ],
                    'extensions' => [
                        'call' => FacebookPagePersonType::class . '::resolve',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function fullPicture($post)
    {
        $id = $post['id'] ?? '';
        $url = $post['full_picture'] ?? '';

        return Util\File::cacheMedia($url, "facebook-page-$id");
    }
}
