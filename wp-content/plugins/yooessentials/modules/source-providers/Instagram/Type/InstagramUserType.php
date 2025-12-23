<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class InstagramUserType extends GenericType
{
    public const NAME = 'InstagramUser';
    public const LABEL = 'User';

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
                'website' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Website',
                    ],
                ],
                'biography' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Biography',
                        'filters' => ['limit'],
                    ],
                ],
                'profile_picture_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Picture URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::profilePictureUrl',
                    ],
                ],
                'followers_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Followers',
                    ],
                ],
                'follows_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Follows',
                    ],
                ],
                'media_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Media',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function profilePictureUrl($user)
    {
        $id = $user['id'] ?? '';
        $url = $user['profile_picture_url'] ?? '';

        if (!$url || !$id) {
            return '';
        }

        return Util\File::cacheMedia($url, "ig-media-user-$id");
    }
}
