<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Bluesky\BlueskySource;

class BlueskyActorAuthorFeedQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'authorFeed';
    public const LABEL = 'Feed';
    public const DESCRIPTION = 'Posts and reposts made by the profile';

    public static function getCacheKey(): string
    {
        return 'bluesky-actor-author-feed';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => BlueskyFeedType::NAME,
                    ],

                    'args' => [
                        'limit' => [
                            'type' => 'Int',
                        ],
                        // 'cursor' => [
                        //     'type' => 'String',
                        // ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            '_pagination' => [
                                'description' => 'Set the start and the number of posts per page.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    // 'cursor' => [
                                    //     'label' => 'Start',
                                    //     'type' => 'yooessentials-number',
                                    //     'default' => 1,
                                    //     'source' => true,
                                    //     'attrs' => [
                                    //         'min' => 1,
                                    //         'placeholder' => 1,
                                    //     ],
                                    // ],
                                    'limit' => [
                                        'label' => 'Count',
                                        'type' => 'yooessentials-number',
                                        'default' => 50,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 100,
                                            'placeholder' => 10,
                                        ],
                                    ],
                                ],
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

    public static function resolve($root, array $args)
    {
        /** @var BlueskySource */
        $source = self::loadSource($args, BlueskySource::class);

        if (!$source) {
            return [];
        }

        $feed = self::resolveFromCache($source, $args, function () use ($source, $args) {
            $actor = $source->actor();

            return $source->api()->getAuthorFeed($actor, $args);
        });

        return $feed;
    }
}
