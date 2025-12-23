<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube;

return [
    'routes' => [
        ['post', YouTubeController::PRESAVE_ENDPOINT, YouTubeController::class . '@presave'],
        ['post', YouTubeController::GET_CHANNELS_ENDPOINT, YouTubeController::class . '@channels'],
        ['post', YouTubeController::GET_PLAYLISTS_ENDPOINT, YouTubeController::class . '@playlists'],
        ['post', YouTubeController::GET_CHANNEL_VIDEOS_ENDPOINT, YouTubeController::class . '@channelVideos'],
        ['post', YouTubeController::GET_CHANNEL_PLAYLISTS_ENDPOINT, YouTubeController::class . '@channelPlaylists'],
        ['post', YouTubeController::GET_PLAYLIST_VIDEOS_ENDPOINT, YouTubeController::class . '@playlistVideos'],
    ],

    'yooessentials-sources' => [
        'youtube' => YouTubeSource::class,
        'youtube-channel' => YouTubeChannelSource::class,
        'youtube-playlist' => YouTubePlaylistSource::class,
    ],
];
