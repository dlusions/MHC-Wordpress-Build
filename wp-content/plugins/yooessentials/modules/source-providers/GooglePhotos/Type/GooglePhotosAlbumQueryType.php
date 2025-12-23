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

class GooglePhotosAlbumQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'album';
    public const LABEL = 'Album';
    public const DESCRIPTION = 'Single album';

    public static function getCacheKey(): string
    {
        return 'google-photos-album';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => GooglePhotosAlbumType::NAME,

                    'args' => [
                        'albumId' => [
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
                                'label' => 'Album',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'Choose the album to query, or set it ID as dynamic.',
                                'endpoint' => 'yooessentials/source/google/photos/albums',
                                'meta' => false,
                                'source' => true,
                                'params' => [
                                    'source_id' => $this->source->id(),
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
        $source = self::loadSource($args, GooglePhotosSource::class);
        $albumId = $args['albumId'] ?? '';

        if (!$source || empty($albumId)) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => (array) $source->api()->album($albumId));
    }
}
