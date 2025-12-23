<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Instagram\InstagramSource;

class InstagramMediaQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'media';
    public const LABEL = 'Media';
    public const DESCRIPTION = 'List of media';

    public static function getCacheKey(): string
    {
        return 'instagram-media';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => InstagramMediaType::NAME,
                    ],

                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'since' => [
                            'type' => 'String',
                        ],
                        'until' => [
                            'type' => 'String',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                        'media_type' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => self::DESCRIPTION,
                        'fields' => [
                            'media_type' => [
                                'type' => 'select',
                                'label' => 'Type',
                                'default' => 'all',
                                'options' => [
                                    'All' => 'all',
                                    'Images' => 'images',
                                    'Videos' => 'videos',
                                ],
                            ],

                            '_media_filter' => [
                                'type' => 'grid',
                                'description' => 'Choose the type and the maximum amount of media to fetch.',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Offset',
                                        'type' => 'yooessentials-number',
                                        'default' => 0,
                                        'source' => true,
                                    ],
                                    'limit' => [
                                        'label' => 'Limit',
                                        'type' => 'yooessentials-number',
                                        'default' => InstagramSource::MEDIA_LIMIT_DEFAULT,
                                        'source' => true,
                                    ],
                                ],
                            ],

                            '_datetime_filter' => [
                                'type' => 'grid',
                                'description' => 'Restrict by start and/or end datetime.',
                                'width' => '1-2',
                                'fields' => [
                                    'since' => [
                                        'label' => 'Since',
                                        'type' => 'yooessentials-datetime',
                                        'source' => true,
                                        'small' => true,
                                        'valueFormat' => 'yyyy-MM-dd HH:mm',
                                        'displayFormat' => 'yyyy-MM-dd HH:mm',
                                    ],

                                    'until' => [
                                        'label' => 'Until',
                                        'type' => 'yooessentials-datetime',
                                        'source' => true,
                                        'small' => true,
                                        'valueFormat' => 'yyyy-MM-dd HH:mm',
                                        'displayFormat' => 'yyyy-MM-dd HH:mm',
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

    public static function resolve($root, array $args): array
    {
        /** @var InstagramSource */
        $source = self::loadSource($args, InstagramSource::class);

        if (!$source) {
            return [];
        }

        return self::resolveFromCache($source, $args, function () use ($source, $args) {
            $limit = $args['limit'] ?? 20;

            return $source->api()->medias($source->pageId(), $limit, $args);
        });
    }
}
