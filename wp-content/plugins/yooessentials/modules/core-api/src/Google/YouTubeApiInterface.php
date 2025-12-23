<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

interface YouTubeApiInterface
{
    public function search(array $args = []): array;

    public function video(string $id): array;

    public function videos(array $ids): array;

    public function channel(string $channelId): array;

    public function channels(array $args): array;

    public function playlists(array $args): array;

    public function channelVideos(string $channelId, array $args = []): array;

    public function playlistVideos(string $playlistId, array $args = []): array;
}
