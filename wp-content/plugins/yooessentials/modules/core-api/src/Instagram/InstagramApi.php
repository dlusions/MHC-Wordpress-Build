<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Instagram;

use ZOOlanders\YOOessentials\Api\Facebook\FacebookBaseApi;

class InstagramApi extends FacebookBaseApi implements InstagramApiInterface
{
    use IteratesOverMedias;

    protected string $instagramApiEndpoint = 'https://graph.instagram.com';

    public function forDriver(string $driverName): self
    {
        if ($driverName === 'facebook') {
            return $this;
        }

        return $this->withEndpoint($this->instagramApiEndpoint);
    }

    protected function getMediaFields(): string
    {
        return 'caption,comments_count,like_count,media_type,media_url,permalink,thumbnail_url,timestamp,username';
    }

    protected function getMediaMaxLimit(): int
    {
        return 100;
    }

    public function media(string $mediaId): array
    {
        $fields = $this->getMediaFields();

        return $this->get($mediaId, ['fields' => $fields]);
    }

    public function children(string $mediaId): array
    {
        $fields = $this->getMediaFields();
        $fields = str_replace('caption,comments_count,like_count,', '', $fields);

        $result = $this->get("$mediaId/children", ['fields' => $fields]);

        return $result['data'] ?? [];
    }

    // https://developers.facebook.com/docs/instagram-api/reference/ig-user/
    public function user(string $userId): array
    {
        $fields = 'biography,id,followers_count,follows_count,media_count,name,profile_picture_url,username,website';

        return $this->get($userId, ['fields' => $fields]);
    }

    public function me(): array
    {
        $fields = 'user_id,name,username';

        return $this->get('me', ['fields' => $fields]);
    }

    public function pages(string $userId, int $limit = 100): array
    {
        $accounts = parent::pages($userId, $limit);

        $pages = array_filter(
            array_map(function ($page) {
                $pageId = $page['id'] ?? '';

                if (!$pageId) {
                    return null;
                }

                try {
                    $result = $this->get($pageId, [
                        'fields' => 'name,instagram_business_account{id}',
                    ]);
                } catch (\Throwable $e) {
                    return null;
                }

                $igAccountId = $result['instagram_business_account']['id'] ?? null;

                if (!$igAccountId) {
                    return null;
                }

                return [
                    'id' => $result['instagram_business_account']['id'] ?? '',
                    'name' => $result['name'] ?? null,
                ];
            }, $accounts)
        );

        return array_values(array_filter($pages));
    }

    /**
     * https://developers.facebook.com/docs/instagram-api/reference/ig-hashtag
     *
     * Max 50 per page
     * Max 30 unique hashtags every 7 days
     * There is no since / until datetime filtering
     *
     * @param string $edge 'top_media' | 'recent_media'
     */
    public function mediaByHashtag(string $pageId, string $hashtag, string $edge, array $filters = []): array
    {
        $fields =
            'caption,comments_count,like_count,media_type,media_url,permalink,timestamp,children{media_url,media_type}';
        $hashtagId = $this->getHashtagId($pageId, $hashtag);

        // We should fetch +offset so if there is an offset of 1, and we want to fetch 20,
        // we fetch 21 and shave off 1 from the head of the lost
        $offset = $filters['offset'] ?? 0;
        $limit = $filters['limit'] ?? 20;
        $originalLimit = $limit;

        if ($offset >= $limit) {
            $offset = 0;
        }
        $resultsToFetch = $limit + $offset;
        $result = $this->fetchHashtagMediaResult($pageId, $hashtagId, $edge, $fields, $resultsToFetch);
        $medias = $result['data'] ?? [];
        $medias = array_slice($medias, $offset);

        while ($originalLimit > count($medias)) {
            // iterate again, with the next page
            $next = $result['paging']['cursors']['after'] ?? null;

            if (!$next) {
                return $medias;
            }

            // Next set
            $limit = $originalLimit - count($medias);

            if ($limit <= 0) {
                return $medias;
            }

            $result = $this->fetchHashtagMediaResult($pageId, $hashtagId, $edge, $fields, $resultsToFetch, $next);
            $medias = array_merge($medias, $result['data'] ?? []);
        }

        return $medias;
    }

    /**
     * https://developers.facebook.com/docs/instagram-api/reference/ig-hashtag-search
     */
    public function getHashtagId(string $pageId, string $hashtag): string
    {
        $result = $this->get('ig_hashtag_search', [
            'user_id' => $pageId,
            'q' => $hashtag,
        ]);

        return $result['data'][0]['id'] ?? '';
    }

    protected function fetchHashtagMediaResult(
        string $pageId,
        string $hashtagId,
        string $edge,
        string $fields,
        int $limit = 20,
        ?string $after = null
    ): array {
        return $this->get(
            "{$hashtagId}/{$edge}",
            array_filter([
                'user_id' => $pageId,
                'fields' => $fields,
                'limit' => $limit,
                'after' => $after,
            ])
        ) ?? [];
    }
}
