<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Twitter\Type;

use ZOOlanders\YOOessentials\Source\Provider\Twitter\TwitterHelper;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class TwitterTweetType extends GenericType
{
    public const NAME = 'TwitterTweet';
    public const LABEL = 'Tweet';

    public function config(): array
    {
        return [
            'fields' => [
                'text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Text',
                        'filters' => ['limit'],
                    ],
                ],
                'text_html' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Text HTML',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::html',
                    ],
                ],
                'permalink_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Permalink',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::permalink',
                    ],
                ],
                'created_at' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'lang' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Language',
                    ],
                ],
                'retweet_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Retweets',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::retweet_count',
                    ],
                ],
                'reply_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Replies',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::reply_count',
                    ],
                ],
                'like_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Likes',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::like_count',
                    ],
                ],
                'quote_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Quotes',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::quote_count',
                    ],
                ],
                'urls' => [
                    'type' => ['listOf' => 'String'],
                    'metadata' => [
                        'label' => 'Urls',
                    ],
                ],
                'expanded_urls' => [
                    'type' => ['listOf' => 'String'],
                    'metadata' => [
                        'label' => 'Expanded Urls',
                    ],
                ],
                'author' => [
                    'type' => TwitterUserType::NAME,
                    'metadata' => [
                        'label' => 'Author',
                    ],
                ],
                'in_reply_to_user' => [
                    'type' => TwitterUserType::NAME,
                    'metadata' => [
                        'label' => 'In Reply To User',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
                'images' => [
                    'type' => ['listOf' => TwitterMediaPhotoType::NAME],
                    'metadata' => [
                        'label' => 'Images',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::images',
                    ],
                ],
                'videos' => [
                    'type' => ['listOf' => TwitterMediaVideoType::NAME],
                    'metadata' => [
                        'label' => 'Videos',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::videos',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function permalink($tweet): string
    {
        return "https://twitter.com/{$tweet['author']['username']}/status/{$tweet['id']}";
    }

    public static function html($tweet): string
    {
        return TwitterHelper::parseText($tweet['text'], $tweet['entities']);
    }

    public static function retweet_count($tweet): int
    {
        return $tweet['public_metrics']['retweet_count'] ?? 0;
    }

    public static function reply_count($tweet): int
    {
        return $tweet['public_metrics']['reply_count'] ?? 0;
    }

    public static function like_count($tweet): int
    {
        return $tweet['public_metrics']['like_count'] ?? 0;
    }

    public static function quote_count($tweet): int
    {
        return $tweet['public_metrics']['quote_count'] ?? 0;
    }

    public static function images($tweet): array
    {
        return array_filter($tweet['medias'] ?? [], fn ($media) => $media['type'] === 'photo');
    }

    public static function videos($tweet): array
    {
        return array_filter($tweet['medias'] ?? [], fn ($media) => $media['type'] === 'video');
    }
}
