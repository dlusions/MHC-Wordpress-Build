<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Cloudflare;

use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\WithAuthentication;
use ZOOlanders\YOOessentials\Api\HasAuthentication;

// https://developers.cloudflare.com/api
class CloudflareApi extends AbstractApi implements HasAuthentication
{
    use WithAuthentication;

    protected string $apiEndpoint = 'https://api.cloudflare.com/client/v4';

    public function accounts()
    {
        return $this->get('accounts');
    }

    public function verifyToken(string $token)
    {
        $this->accessToken = $token;

        try {
            return $this->get('user/tokens/verify');
        } catch (\Throwable $e) {
            throw new \Exception('Invalid Token.');
        }
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

        if (!$result) {
            throw new \Exception((string) $response->getBody());
        }

        $success =
            $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && $result['success'];

        if (!$success) {
            $code = $result['errors'][0]['code'] ?? ($response->getStatusCode() ?? 400);
            $message = $result['errors'][0]['message'] ?? ($response->getStatusCode() ?? 'Unknown Error');

            if ($code === 10000) {
                $message = 'The API Token is missing the permissions for this operation.';
            }

            throw new \Exception($message, $code);
        }

        return $result['result'] ?? [];
    }
}
