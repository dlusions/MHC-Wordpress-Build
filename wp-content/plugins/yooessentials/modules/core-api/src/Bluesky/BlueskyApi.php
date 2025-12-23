<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Bluesky;

use YOOtheme\Arr;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;

class BlueskyApi extends AbstractApi implements BlueskyApiInterface
{
    protected string $apiEndpoint = 'https://public.api.bsky.app/xrpc';

    public function searchActors(array $args = []): array
    {
        $result = $this->get('app.bsky.actor.searchActors', Arr::pick($args, ['q', 'limit', 'cursor']));

        return $result['actors'] ?? [];
    }

    public function getLists(string $actor, array $args = []): array
    {
        $result = $this->get('app.bsky.graph.getLists', array_merge(
            [
                'actor' => $actor,
            ],
            Arr::pick($args, ['limit', 'cursor'])
        ));

        return $result['lists'] ?? [];
    }

    public function getListFeed(string $list, array $args = []): array
    {
        $result = $this->get('app.bsky.feed.getListFeed', array_merge(
            [
                'list' => $list,
            ],
            Arr::pick($args, ['limit', 'cursor'])
        ));

        return $result['feed'] ?? [];
    }

    public function getFeed(string $feed, array $args = []): array
    {
        $result = $this->get('app.bsky.feed.getFeed', array_merge(
            [
                'feed' => $feed,
            ],
            Arr::pick($args, ['limit', 'cursor'])
        ));

        return $result['feed'] ?? [];
    }

    public function getAuthorFeed(string $actor, array $args = []): array
    {
        $result = $this->get('app.bsky.feed.getAuthorFeed', array_merge(
            [
                'actor' => $actor,
            ],
            Arr::pick($args, ['limit', 'cursor', 'filter', 'includePins'])
        ));

        return $result['feed'] ?? [];
    }

    public function getProfile(string $actor): array
    {
        $result = $this->get('app.bsky.actor.getProfile', [
            'actor' => $actor,
        ]);

        return $result ?? [];
    }

    public function searchPosts(array $args): array
    {
        $result = $this->get(
            'app.bsky.feed.searchPosts',
            Arr::pick(
                $args,
                [
                    'limit',
                    // 'cursor',
                    'sort',
                    'q',
                    'author',
                    'since',
                    'until',
                    'mentions',
                    'lang',
                    'domain',
                    'url',
                    'tag',
                ]
            )
        );

        return $result['posts'] ?? [];
    }

    protected function processResponse(Response $response): array
    {
        $result = json_decode($response->getBody(), true);

        $success =
            $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && ($result['error'] ?? null) === null;

        if (!$success) {
            $code = $response->getStatusCode() ?? 400;
            $message = $result['message'] ?? $result['error'] ?? 'Unknown Error';

            throw new \Exception($message, $code);
        }

        return $result;
    }
}
