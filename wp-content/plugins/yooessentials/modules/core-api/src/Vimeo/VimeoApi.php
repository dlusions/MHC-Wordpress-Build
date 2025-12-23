<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Vimeo;

use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\WithAuthentication;
use ZOOlanders\YOOessentials\Api\HasAuthentication;

// https://developer.vimeo.com/api/reference
class VimeoApi extends AbstractApi implements VimeoApiInterface, HasAuthentication
{
    use WithAuthentication;

    protected string $apiEndpoint = 'https://api.vimeo.com';

    public function videos(array $args = []): array
    {
        $result = $this->get('videos', $args);

        return $result['data'] ?? [];
    }

    public function userVideos(string $userId, array $args = []): array
    {
        $result = $this->get("users/$userId/videos", $args);

        return $result['data'] ?? [];
    }

    public function myVideos(array $args = []): array
    {
        $result = $this->get('me/videos', $args);

        return $result['data'] ?? [];
    }

    public function myShowcaseVideos(string $showcaseId, array $args = []): array
    {
        $result = $this->get("me/albums/{$showcaseId}/videos", $args);

        return $result['data'] ?? [];
    }

    public function myFolderVideos(string $folderId, array $args = []): array
    {
        $result = $this->get("me/projects/{$folderId}/videos", $args);

        return $result['data'] ?? [];
    }

    public function verifyToken(string $token)
    {
        $this->accessToken = $token;

        return $this->get('oauth/verify');
    }

    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();
        $headers += $this->getAuthorizationHeader();

        $headers += [
            'User-Agent' => 'YOOessentials/1.0.0',
            'Accept' => 'application/vnd.vimeo.*+json;version=3.4'
        ];

        return $headers;
    }

    protected function processResponse(Response $response): array
    {
        $result = json_decode($response->getBody(), true);
        $success =
            $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && ($result['message'] ?? '') !== 'error';

        if (!$success) {
            $code = $result['error_code'] ?? ($response->getStatusCode() ?? 400);
            $message = $result['error'] ?? ($response->getReasonPhrase() ?? 'Unknown Error');

            throw new \Exception($message, $code);
        }

        return $result;
    }
}
