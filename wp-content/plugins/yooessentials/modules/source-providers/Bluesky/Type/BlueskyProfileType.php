<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky\Type;

use YOOtheme\Url;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class BlueskyProfileType extends GenericType
{
    public const NAME = 'BlueskyProfile';
    public const LABEL = 'Profile';

    public function config(): array
    {
        return [
            'fields' => [
                'did' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'DID',
                        'description' => 'Unique identifier',
                    ],
                ],
                'handle' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Handle',
                        'description' => 'Unique alias used as username and url for the profile',
                    ],
                ],
                'displayName' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Display name',
                        'description' => 'Profile display name',
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'description' => 'Profile description',
                        'filters' => ['limit'],
                    ],
                ],
                'avatar' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Avatar',
                        'description' => 'Profile avatar URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveAvatar',
                    ],
                ],
                'banner' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Banner',
                        'description' => 'Profile banner URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveBanner',
                    ],
                ],
                'createdAt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'description' => 'Profile date of creation',
                        'filters' => ['date'],
                    ],
                ],
                'followersCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Followers Count',
                        'description' => 'Total number of followers',
                    ],
                ],
                'followsCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Follows Count',
                        'description' => 'Total number of follows',
                    ],
                ],
                'postsCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Posts Count',
                        'description' => 'Total number of posts',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveAvatar(array $actor): string
    {
        $id = $actor['did'];
        $url = $actor['avatar'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "bluesky-profile-avatar-$id");
        }

        return '';
    }

    public static function resolveBanner(array $actor): string
    {
        $id = $actor['did'];
        $url = $actor['banner'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "bluesky-profile-banner-$id");
        }

        return '';
    }
}
