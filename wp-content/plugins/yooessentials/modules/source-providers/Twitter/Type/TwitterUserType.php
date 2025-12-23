<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Twitter\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class TwitterUserType extends GenericType
{
    public const NAME = 'TwitterUser';
    public const LABEL = 'User';

    public function config(): array
    {
        return [
            'fields' => [
                'username' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Username',
                    ],
                ],
                'created_at' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile URL',
                    ],
                ],
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile Name',
                    ],
                ],
                'profile_image' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile Image',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::profileImage',
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile Description',
                        'filters' => ['limit'],
                    ],
                ],
                'followers_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Followers',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::followers_count',
                    ],
                ],
                'following_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Following',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::following_count',
                    ],
                ],
                'tweet_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Tweets',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tweet_count',
                    ],
                ],
                'listed_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Listed',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::listed_count',
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

    public static function followers_count($user): int
    {
        return $user['public_metrics']['followers_count'] ?? 0;
    }

    public static function following_count($user): int
    {
        return $user['public_metrics']['following_count'] ?? 0;
    }

    public static function tweet_count($user): int
    {
        return $user['public_metrics']['tweet_count'] ?? 0;
    }

    public static function listed_count($user): int
    {
        return $user['public_metrics']['listed_count'] ?? 0;
    }

    public static function profileImage($user)
    {
        $id = $user['id'] ?? '';
        $url = $user['profile_image_url'] ?? '';

        return Util\File::cacheMedia($url, "twitter-user-$id");
    }
}
