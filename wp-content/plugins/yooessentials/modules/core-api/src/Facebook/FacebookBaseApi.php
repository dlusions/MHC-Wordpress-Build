<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Facebook;

use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\HttpClientInterface;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\HasAuthentication;
use ZOOlanders\YOOessentials\Api\WithAuthentication;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;

class FacebookBaseApi extends AbstractApi implements HasAuthentication
{
    use WithAuthentication;

    protected ?CacheInterface $cache = null;

    protected string $apiEndpoint = 'https://graph.facebook.com/v23.0';

    public function __construct(CacheInterface $cache, HttpClientInterface $client)
    {
        parent::__construct($client);

        $this->cache = $cache;
    }

    public function me(): array
    {
        return $this->get('me');
    }

    public function debugToken(string $debugToken, string $clientId, string $clientSecret): array
    {
        // app access token is required for debugging
        $this->withAccessToken("$clientId|$clientSecret");

        $result = $this->get('debug_token', [
            'input_token' => $debugToken,
        ]);

        return $result['data'] ?? [];
    }

    public function pages(string $userId, int $limit = 100): array
    {
        $result = $this->fetchPages($userId, $limit);
        $pages = $this->filterPages($result['data'] ?? []);
        $originalLimit = $limit;

        while ($originalLimit > count($pages)) {
            // iterate again, with the next page
            $next = $result['paging']['cursors']['after'] ?? null;

            if (!$next) {
                return $pages;
            }

            // Next set
            $limit = $originalLimit - count($pages);

            if ($limit <= 0) {
                return $pages;
            }

            $result = $this->fetchPages($userId, $limit, $next);
            $nextPages = $this->filterPages($result['data'] ?? []);
            $pages = array_merge($pages, $nextPages);
        }

        return array_map(fn (array $page) => [
            'id' => $page['id'],
            'name' => $page['name'] ?? $page['id'],
        ], $pages);
    }

    protected function filterPages(array $pages): array
    {
        return array_values(array_filter($pages, function (array $page) {
            $pageId = $page['id'] ?? null;
            if (!$pageId) {
                return false;
            }

            // You can't manage the page, so no sense displaying it
            $token = $page['access_token'] ?? null;
            if (!$token) {
                return false;
            }

            return true;
        }));
    }

    protected function fetchPages(string $userId, int $limit = 100, ?string $after = null): array
    {
        return $this->get("$userId/accounts", ['limit' => $limit, 'after' => $after]);
    }

    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();
        $headers += $this->getAuthorizationHeader();

        return $headers;
    }

    protected function processResponse(Response $response): array
    {
        $result = json_decode($response->getBody(), true);
        $success = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299;

        if (!$success) {
            $code = $response->getStatusCode() ?? 400;
            $message = $result['error']['message'] ?? ($response->getReasonPhrase() ?? 'Unknown Error');

            throw new \Exception($message, $code);
        }

        return $result;
    }
}
