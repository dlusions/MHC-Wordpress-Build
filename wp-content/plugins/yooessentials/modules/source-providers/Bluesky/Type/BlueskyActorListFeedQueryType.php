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

class BlueskyActorListFeedQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'listFeed';
    public const LABEL = 'List Feed';
    public const DESCRIPTION = 'Posts and reposts from any profiles on the list';

    public static function getCacheKey(): string
    {
        return 'bluesky-actor-list-feed';
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
                        'list' => [
                            'type' => 'String',
                        ],
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
                            'list' => [
                                'label' => 'List',
                                'description' => 'The actor list from which to create the source.',
                                'type' => 'yooessentials-select-dropdown-async',
                                'endpoint' => 'yooessentials/source/bluesky/actor/lists',
                                'source' => true,
                                'params' => [
                                    'source_id' => $this->source->id(),
                                ],
                            ],
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

        $list = $source->config('list') ?? null;

        if (!$source || !$list) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $source->api()->getListFeed($list, $args));
    }
}
