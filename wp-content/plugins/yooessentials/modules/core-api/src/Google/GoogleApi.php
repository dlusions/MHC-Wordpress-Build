<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\HasAuthentication;
use ZOOlanders\YOOessentials\Api\WithAuthentication;

class GoogleApi extends AbstractApi implements HasAuthentication
{
    use WithAuthentication;

    public function refreshAccessToken(string $clientId, string $clientSecret, string $refreshToken): array
    {
        $response = $this->client->post(
            'https://oauth2.googleapis.com/token',
            [
                'client_id' => $clientId,
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

    protected function request(string $method, string $name, array $args): array
    {
        if ($this->apiKey) {
            $args['key'] = $this->apiKey;
        }

        return parent::request($method, $name, $args);
    }

    protected function processResponse(Response $response): array
    {
        $result = json_decode($response->getBody(), true);
        $success =
            $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && ($result['success'] ?? '') !== false;

        if (!$success) {
            $message = $result['error']['message']
                ?? $result['error_description']
                ?? $response->getReasonPhrase()
                ?? 'Unknown Error';

            $code = $result['error']['code']
                ?? $response->getStatusCode()
                ?? 400;

            throw new \Exception($message, $code);
        }

        return $result;
    }
}
