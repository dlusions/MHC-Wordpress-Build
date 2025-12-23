<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GooglePhotos\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\GooglePhotos\GooglePhotosSource;

class GooglePhotosAlbumMediaQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'albumMedia';
    public const LABEL = 'Album Media';
    public const DESCRIPTION = 'List of the album media';

    public static function getCacheKey(): string
    {
        return 'google-photos-album-media';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => GooglePhotosMediaType::NAME,
                    ],

                    'args' => [
                        'albumId' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'pageSize' => [
                            'type' => 'Int',
                        ],
                        'media_type' => [
                            'type' => 'String',
                        ],
                        'orderBy' => [
                            'type' => 'String',
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
                            'albumId' => [
                                'label' => 'Album ID',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'Choose the album which media to query, or set it ID as dynamic.',
                                'endpoint' => 'yooessentials/source/google/photos/albums',
                                'meta' => false,
                                'source' => true,
                                'params' => [
                                    'source_id' => $this->source->id(),
                                ],
                            ],
                            '_media_filter_order' => [
                                'type' => 'grid',
                                'description' => 'Set the type and order by which to query the media.',
                                'width' => '1-2',
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
                                    'orderBy' => [
                                        'label' => 'Order By',
                                        'type' => 'select',
                                        'default' => 'MediaMetadata.creation_time desc',
                                        'options' => [
                                            'Date (desc)' => 'MediaMetadata.creation_time desc',
                                            'Date (asc)' => 'MediaMetadata.creation_time',
                                        ]
                                    ],
                                ]
                            ],
                            '_media_offset_limit' => [
                                'type' => 'grid',
                                'description' => 'Set the start and the maximum amount of media to query.',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                    'pageSize' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => GooglePhotosSource::PAGE_SIZE_DEFAULT,
                                        'source' => true,
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

    public static function resolve($root, array $args, $ctx)
    {
        $source = self::loadSource($args, GooglePhotosSource::class);
        $albumId = $args['albumId'] ?? '';

        if (!$source || empty($albumId)) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => (array) $source->api()->mediaItemsSearch($args));
    }
}
