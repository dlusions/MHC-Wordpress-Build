<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Bluesky;

// https://docs.bsky.app/docs/api/at-protocol-xrpc-api
interface BlueskyApiInterface
{
    // https://docs.bsky.app/docs/api/app-bsky-actor-search-actors
    public function searchActors(array $args = []): array;

    // https://docs.bsky.app/docs/api/app-bsky-graph-get-lists
    public function getLists(string $actor, array $args = []): array;

    // https://docs.bsky.app/docs/api/app-bsky-feed-get-list-feed
    public function getListFeed(string $list, array $args = []): array;

    // https://docs.bsky.app/docs/api/app-bsky-feed-get-feed
    public function getFeed(string $feed, array $args = []): array;

    // https://docs.bsky.app/docs/api/app-bsky-feed-get-author-feed
    public function getAuthorFeed(string $actor, array $args = []): array;

    // https://docs.bsky.app/docs/api/app-bsky-actor-get-profile
    public function getProfile(string $actor): array;

    // https://docs.bsky.app/docs/api/app-bsky-feed-search-posts
    public function searchPosts(array $args): array;
}
