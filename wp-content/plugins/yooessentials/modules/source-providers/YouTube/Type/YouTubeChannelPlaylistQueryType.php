<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\YouTube\YouTubeChannelSource;
use ZOOlanders\YOOessentials\Source\Provider\YouTube\YouTubeController;

class YouTubeChannelPlaylistQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const MIN_CACHE_TIME = 3600;

    public const NAME = 'playlist';
    public const LABEL = 'Playlist';
    public const DESCRIPTION = 'Playlist from the channel';

    public static function getCacheKey(): string
    {
        return 'youtube-channel-playlist';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => YouTubePlaylistType::NAME,

                    'args' => [
                        'playlist_id' => [
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
                            'playlist_id' => [
                                'label' => 'Playlist',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'The playlist which content to fetch.',
                                'endpoint' => YouTubeController::GET_CHANNEL_PLAYLISTS_ENDPOINT,
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
        /** @var YouTubeChannelSource */
        $source = self::loadSource($args, YouTubeChannelSource::class);

        $playlistId = $args['playlist_id'] ?? null;

        if (!$source || !$playlistId) {
            return;
        }

        return self::resolveFromCache($source, $args, function () use ($source, $args, $playlistId) {
            $playlist = $source->api()->playlists(['id' => $playlistId])[0] ?? null;
            if ($playlist === null) {
                return [];
            }

            $playlist['videos'] = $source->api()->playlistVideos($playlistId);

            return $playlist;
        });
    }
}
