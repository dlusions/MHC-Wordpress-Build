<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube;

use function YOOtheme\app;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\Google\YouTubeApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class YouTubeController
{
    use LoadsSourceFromArgs;

    public const PRESAVE_ENDPOINT = 'yooessentials/source/youtube';
    public const GET_CHANNELS_ENDPOINT = 'yooessentials/source/youtube/channels';
    public const GET_PLAYLISTS_ENDPOINT = 'yooessentials/source/youtube/playlists';
    public const GET_CHANNEL_VIDEOS_ENDPOINT = 'yooessentials/source/youtube/channel/videos';
    public const GET_CHANNEL_PLAYLISTS_ENDPOINT = 'yooessentials/source/youtube/channel/playlists';
    public const GET_PLAYLIST_VIDEOS_ENDPOINT = 'yooessentials/source/youtube/playlist/videos';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $account = $form['account'] ?? null;
        $provider = $form['provider'] ?? null;
        $channelId = $form['channel_id'] ?? null;
        $playlistId = $form['playlist_id'] ?? null;

        if (!$account && $provider !== 'youtube') {
            return $response->withJson('Account must be specified.', 400);
        }

        if ($provider === 'youtube_channel' && !$channelId) {
            return $response->withJson('Channel must be specified.', 400);
        }

        if ($provider === 'youtube_playlist' && !$playlistId) {
            return $response->withJson('Playlist must be specified.', 400);
        }

        return $response->withJson(200);
    }

    public function channelVideos(Request $request, Response $response)
    {
        try {
            /** @var GoogleBusinessProfileSource */
            $source = self::loadSource($request->getParsedBody(), GoogleBusinessProfileSource::class);

            $items = $source->api()->channelVideos($source->channel, ['part' => 'snippet']);

            $result = array_map(fn ($item) => [
                'value' => $item['id'],
                'text' => $item['snippet']['title'] ?? '',
                'meta' => $item['snippet']['description'] ?: $item['id'],
            ], $items);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function playlistVideos(Request $request, Response $response)
    {
        try {
            /** @var GoogleBusinessProfileSource */
            $source = self::loadSource($request->getParsedBody(), GoogleBusinessProfileSource::class);

            $items = $source->api()->playlistVideos($source->playlist);

            $result = array_map(fn ($item) => [
                'value' => $item['id'],
                'text' => $item['snippet']['title'] ?? '',
                'meta' => $item['snippet']['description'] ?: $item['id'],
            ], $items);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function channels(Request $request, Response $response)
    {
        $form = $request->getParam('form');

        try {
            $api = $this->initApi($form);
            $channels = $api->channels(['mine' => true, 'part' => 'snippet,statistics,status']);

            $result = array_map(fn ($channel) => [
                'value' => $channel['id'],
                'text' => $channel['snippet']['title'],
                'meta' => [
                    'id' => $channel['id'],
                    'videos' => $channel['statistics']['videoCount'],
                    'privacy' => $channel['status']['privacyStatus'],
                ],
            ], $channels);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function playlists(Request $request, Response $response)
    {
        $form = $request->getParam('form');

        try {
            $api = $this->initApi($form);
            $playlists = $api->playlists(['mine' => true, 'part' => 'snippet,contentDetails,status']) ?? [];

            $result = array_map(fn ($playlist) => [
                'value' => $playlist['id'],
                'text' => $playlist['snippet']['title'],
                'meta' => [
                    'id' => $playlist['id'],
                    'videos' => $playlist['contentDetails']['itemCount'],
                    'privacy' => $playlist['status']['privacyStatus'],
                ],
            ], $playlists);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function channelPlaylists(Request $request, Response $response)
    {
        /** @var GoogleBusinessProfileSource */
        $source = self::loadSource($request->getParsedBody(), GoogleBusinessProfileSource::class);

        try {
            $playlists =
                $source->api()->playlists([
                    'channelId' => $source->channel,
                    'part' => 'snippet,contentDetails,status',
                ]);

            $result = array_map(fn ($playlist) => [
                'value' => $playlist['id'],
                'text' => $playlist['snippet']['title'],
                'meta' => [
                    'id' => $playlist['id'],
                    'videos' => $playlist['contentDetails']['itemCount'],
                    'privacy' => $playlist['status']['privacyStatus'],
                ],
            ], $playlists);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    protected function initApi(array $data): YouTubeApiInterface
    {
        $account = $data['account'] ?? null;

        if (!$account) {
            throw new \Exception('Account must be specified.');
        }

        $authManager = app(AuthManager::class);
        $auth = $authManager->auth($account);

        if (!$auth) {
            throw new \Exception('Invalid Auth.');
        }

        if (!$auth->accessToken ?? false) {
            throw new \Exception('Account Token is invalid.');
        }

        return app(YouTubeApiInterface::class)->withAccessToken($auth->accessToken());
    }
}
