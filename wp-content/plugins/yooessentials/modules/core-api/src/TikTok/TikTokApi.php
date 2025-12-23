<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\TikTok;

use YOOtheme\Arr;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\HasAuthentication;
use ZOOlanders\YOOessentials\Api\WithAuthentication;

class TikTokApi extends AbstractApi implements TikTokApiInterface, HasAuthentication
{
    use WithAuthentication;

    // prettier-ignore
    protected const VIDEO_FIELDS = ['id', 'create_time', 'cover_image_url', 'share_url', 'video_description', 'duration', 'height', 'width', 'title', 'embed_html', 'embed_link', 'like_count', 'comment_count', 'share_count', 'view_count'];

    protected string $apiEndpoint = 'https://open.tiktokapis.com/v2';

    public function queryVideos(array $filters = []): array
    {
        $result = $this->post(
            'video/query/?fields=' . implode(',', self::VIDEO_FIELDS),
            [
                'filters' => Arr::pick($filters, ['video_ids'])
            ]
        );

        return $result['videos'] ?? [];
    }

    public function listVideos(array $filter = []): array
    {
        $result = $this->post(
            'video/list/?fields=' . implode(',', self::VIDEO_FIELDS),
            Arr::pick($filter, ['cursor', 'max_count'])
        );

        return $result['videos'] ?? [];
    }

    public function refreshAccessToken(string $clientKey, string $clientSecret, string $refreshToken): array
    {
        $response = $this->client->post(
            $this->apiEndpoint . '/oauth/token/',
            [
                'client_key' => $clientKey,
                'client_secret' => $clientSecret,
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
        $result = json_decode($response->getBody(), true);
        $success = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299;

        if (!$success) {
            $code = $result['error']['code'] ?? ($result['data']['error_code'] ?? ($response->getStatusCode() ?? 400));

            $message =
                $result['error']['message'] ??
                $result['error_description'] ??
                $result['error'] ??
                $result['data']['description'] ??
                ($response->getReasonPhrase() ?? 'Unknown Error');

            throw new \Exception($message, (int) $code);
        }

        return $result['data'] ?? $result;
    }
}
