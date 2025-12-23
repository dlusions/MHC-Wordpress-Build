<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

// https://developers.google.com/youtube/v3
class YouTubeApi extends GoogleApi implements YouTubeApiInterface
{
    protected string $apiEndpoint = 'https://www.googleapis.com/youtube/v3';

    public function search(array $args = []): array
    {
        if (!empty($args['channelId'])) {
            $channel = $this->channel($args['channelId']);

            if (!isset($channel['id'])) {
                throw new \Exception(sprintf('Channel ID not valid.', $args['channelId']));
            }
        }

        return $this->get(
            'search',
            array_merge(
                [
                    'maxResults' => 50,
                    'part' => 'snippet',
                ],
                $args
            )
        )['items'] ?? [];
    }

    public function video(string $id): array
    {
        return $this->videos([$id])[0] ?? [];
    }

    public function videos(array $ids, array $args = []): array
    {
        if (!count($ids)) {
            return [];
        }

        return $this->get(
            'videos',
            array_merge(
                [
                    'id' => implode(',', $ids),
                    'part' => 'snippet,contentDetails,statistics',
                ],
                $args
            )
        )['items'] ?? [];
    }

    public function channel(string $channelId, array $args = []): array
    {
        return $this->channels(['id' => $channelId] + $args)[0] ?? [];
    }

    public function channels(array $args = []): array
    {
        return $this->get(
            'channels',
            array_merge(
                [
                    'maxResults' => 50,
                    'part' => 'snippet',
                ],
                $args
            )
        )['items'] ?? [];
    }

    public function playlists(array $args = []): array
    {
        $result = $this->get(
            'playlists',
            array_merge(
                [
                    'maxResults' => 50,
                    'part' => 'snippet',
                ],
                $args
            )
        );

        return $result['items'] ?? [];
    }

    public function channelVideos(string $channelId, array $args = []): array
    {
        $channel = $this->channel($channelId, ['part' => 'contentDetails']);
        $playlistId = $channel['contentDetails']['relatedPlaylists']['uploads'] ?? '';

        if (!$playlistId) {
            return [];
        }

        $items = $this->playlistItems($playlistId, $args);

        return $this->videos(
            array_map(fn ($item) => $item['snippet']['resourceId']['videoId'], $items),
            $args
        );
    }

    public function playlistVideos(string $playlistId, array $args = []): array
    {
        $items = $this->playlistItems($playlistId, $args);

        return $this->videos(
            array_map(fn ($item) => $item['snippet']['resourceId']['videoId'], $items),
            $args
        );
    }

    protected function playlistItems(string $playlistId, array $args = []): array
    {
        return $this->get(
            'playlistItems',
            array_merge(
                [
                    'maxResults' => 50,
                    'part' => 'snippet',
                    'playlistId' => $playlistId,
                ],
                $args
            )
        )['items'] ?? [];
    }
}
