<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Twitter\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Twitter\TwitterSource;

class TwitterMyTweetsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'tweets';
    public const LABEL = 'Tweets';
    public const DESCRIPTION = 'List of posts authored by the authenticated user';

    public static function getCacheKey(): string
    {
        return 'twitter-my-tweets';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => TwitterTweetType::NAME,
                    ],

                    'args' => [
                        'start_time' => [
                            'type' => 'String',
                        ],

                        'end_time' => [
                            'type' => 'String',
                        ],

                        'limit' => [
                            'type' => 'Int',
                        ],

                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            '_datetime_filter' => [
                                'type' => 'grid',
                                'description' => 'Restrict the tweets by it start and/or end datetime.',
                                'width' => '1-2',
                                'fields' => [
                                    'start_time' => [
                                        'label' => 'Since',
                                        'type' => 'yooessentials-datetime',
                                        'source' => true,
                                        'small' => true,
                                        'valueFormat' => 'yyyy-MM-dd HH:mm',
                                        'displayFormat' => 'yyyy-MM-dd HH:mm',
                                    ],

                                    'end_time' => [
                                        'label' => 'Until',
                                        'type' => 'yooessentials-datetime',
                                        'source' => true,
                                        'small' => true,
                                        'valueFormat' => 'yyyy-MM-dd HH:mm',
                                        'displayFormat' => 'yyyy-MM-dd HH:mm',
                                    ],
                                ],
                            ],

                            'limit' => [
                                'label' => 'Limit',
                                'type' => 'yooessentials-number',
                                'description' => 'The maximum amount of tweets to fetch.',
                                'default' => TwitterSource::TWEETS_DEFAULT_LIMIT,
                                'source' => true,
                            ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args): array
    {
        /** @var TwitterSource */
        $source = self::loadSource($args, TwitterSource::class);

        if (!$source) {
            return [];
        }

        foreach (['start_time', 'end_time'] as $field) {
            if (isset($args[$field])) {
                $args[$field] = \DateTime::createFromFormat('Y-m-d H:i', $args[$field])->format(DATE_ATOM);
            }
        }

        return self::resolveFromCache($source, $args, fn () => $source->api()->tweets($source->auth()->userId(), $args['limit'] ?? null, $args));
    }
}
