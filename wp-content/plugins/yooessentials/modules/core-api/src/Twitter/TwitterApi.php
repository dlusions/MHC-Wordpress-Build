<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Twitter;

use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\HttpClientInterface;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\WithAuthentication;
use ZOOlanders\YOOessentials\Api\HasAuthentication;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;

class TwitterApi extends AbstractApi implements TwitterApiInterface, HasAuthentication
{
    use WithAuthentication;

    protected CacheInterface $cache;

    protected string $apiEndpoint = 'https://api.twitter.com/2';

    protected const TWEET_FIELDS = [
        'id',
        'text',
        'attachments',
        'author_id',
        'created_at',
        'entities',
        'in_reply_to_user_id',
        'lang',
        'public_metrics',
        'source',
    ];

    protected const MEDIA_FIELDS = [
        'duration_ms',
        'height',
        'media_key',
        'preview_image_url',
        'type',
        'url',
        'width',
        'public_metrics',
        'non_public_metrics',
        'organic_metrics',
        'promoted_metrics',
        'alt_text',
        'variants',
    ];

    protected const USER_FIELDS = [
        'created_at',
        'description',
        'entities',
        'id',
        'location',
        'name',
        'pinned_tweet_id',
        'profile_image_url',
        'protected',
        'public_metrics',
        'url',
        'username',
        'verified',
        'withheld',
    ];

    public function __construct(CacheInterface $cache, HttpClientInterface $client)
    {
        parent::__construct($client);

        $this->cache = $cache;
    }

    /**
     * @see https://docs.x.com/x-api/users/user-lookup-me
     */
    public function account(array $args = []): array
    {
        $result = $this->get('users/me', [
            'user.fields' => implode(',', self::USER_FIELDS)
        ] + $args);

        return $result['data'] ?? [];
    }

    /**
     * @see https://developer.twitter.com/en/docs/twitter-api/tweets/timelines/api-reference/get-users-id-tweets
     */
    public function tweets(string $userId, ?int $limit = 20, array $filters = []): array
    {
        $result = $this->fetchTweetsResults($userId, $limit, null, $filters);
        $data = $result['data'] ?? [];

        $data = $this->mapTweets($data, $result['includes'] ?? []);
        $originalLimit = $limit;

        while ($originalLimit > count($data)) {
            // iterate again, with the next page
            $next = $result['meta']['next_token'] ?? null;

            if (!$next) {
                return $data;
            }

            // Next set
            $limit = $originalLimit - count($data);

            if ($limit <= 0) {
                return $data;
            }

            $result = $this->fetchTweetsResults($userId, $limit, $next, $filters);
            $data = array_merge($data, $this->mapTweets($result['data'], $result['includes']));
        }

        return $data;
    }

    private function mapTweetData(array $users, array $tweet, array $medias): array
    {
        $tweet['author'] = $users[$tweet['author_id']] ?? [];
        $tweet['in_reply_to_user'] = isset($tweet['in_reply_to_user_id']) ? $users[$tweet['in_reply_to_user_id']] ?? [] : [];
        $tweet['medias'] = [];
        $tweet['urls'] = [];
        $tweet['expanded_urls'] = [];

        foreach ($tweet['entities']['urls'] ?? [] as $url) {
            $tweet['urls'][] = $url['url'];
            $tweet['expanded_urls'][] = $url['expanded_url'];
        }

        foreach ($tweet['attachments']['media_keys'] ?? [] as $media) {
            $tweet['medias'][] = $medias[$media] ?? [];
        }

        return $tweet;
    }

    private function indexUsers(array $users1): array
    {
        $includedUsers = $users1;

        $users = [];
        foreach ($includedUsers as $user) {
            $users[$user['id']] = $user;
        }

        return $users;
    }

    private function indexMedias(array $media1): array
    {
        $includedMedias = $media1;

        $medias = [];
        foreach ($includedMedias as $media) {
            $medias[$media['media_key']] = $media;
        }

        return $medias;
    }

    /**
     * @see https://docs.twitter.com/resources/fundamentals/authentication/api-reference#post-oauth2%2Ftoken
     */
    public function refreshAccessToken(string $clientId, string $refreshToken): array
    {
        $response = $this->client->post(
            "$this->apiEndpoint/oauth2/token",
            [
                'client_id' => $clientId,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ],
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]
        );

        return $this->processResponse($response);
    }

    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();
        $headers += $this->getAuthorizationHeader();

        return $headers;
    }

    protected function processResponse(Response $response): array
    {
        $body = json_decode($response->getBody(), true);
        $success = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && is_array($body);

        if (!$success) {
            $message = $body['errors'][0]['message']
                ?? $body['error_description']
                ?? $body['detail']
                ?? $response->getReasonPhrase()
                ?? 'Unknown Error';

            $code = $body['errors'][0]['code']
                ?? $body['status']
                ?? $response->getStatusCode()
                ?? 400;

            throw new \Exception($message, $code);
        }

        return $body;
    }

    private function fetchTweetsResults(
        string $userId,
        int $limit = 20,
        ?string $pagination_token = null,
        array $filters = []
    ): array {
        try {
            return $this->fetchTweetsRequest($userId, $limit, $pagination_token, $filters);
        } catch (\Throwable $e) {
            // Twitter apis sometimes, especially with low limits like 2,
            // goes into a 400 error. We try then to call with a higher limit
            // and manually slice the result
            if ($e->getCode() !== 400) {
                throw $e;
            }

            $response = $this->fetchTweetsRequest($userId, 20, $pagination_token, $filters);

            $data = $response['data'] ?? [];
            $response['data'] = array_slice($data, 0, min($limit, 100));

            return $response;
        }
    }

    private function mapTweets(array $data, array $includes): array
    {
        $users = $this->indexUsers($includes['users'] ?? []);
        $medias = $this->indexMedias($includes['media'] ?? []);

        return array_map(fn (array $tweet) => $this->mapTweetData($users, $tweet, $medias), $data);
    }

    private function fetchTweetsRequest(
        string $userId,
        int $limit = 20,
        ?string $paginationToken = null,
        array $filters = []
    ): array {
        return $this->get(
            "users/{$userId}/tweets",
            array_filter([
                'tweet.fields' => implode(',', self::TWEET_FIELDS),
                'user.fields' => implode(',', self::USER_FIELDS),
                'media.fields' => implode(',', self::MEDIA_FIELDS),
                'expansions' => 'author_id,in_reply_to_user_id,attachments.media_keys',
                'max_results' => $limit,
                'pagination_token' => $paginationToken,
                'start_time' => $filters['start_time'] ?? null,
                'end_time' => $filters['end_time'] ?? null,
            ])
        );
    }
}
