<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api;

use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\HttpClientInterface;

abstract class AbstractApi
{
    protected string $apiEndpoint = '';

    protected HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function withEndpoint(string $endpoint): self
    {
        $this->apiEndpoint = $endpoint;

        return $this;
    }

    protected function getUrl(string $name): string
    {
        return $this->apiEndpoint ? "{$this->apiEndpoint}/{$name}" : $name;
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function get(string $name, array $args = []): array
    {
        return $this->request('GET', $name, $args);
    }

    public function post(string $name, array $args = []): array
    {
        return $this->request('POST', $name, $args);
    }

    public function put(string $name, array $args = []): array
    {
        return $this->request('PUT', $name, $args);
    }

    public function patch(string $name, array $args = []): array
    {
        return $this->request('PATCH', $name, $args);
    }

    /**
     * Alias for the patch method
     */
    public function update(string $name, array $args = []): array
    {
        return $this->patch($name, $args);
    }

    public function delete(string $name, array $args = []): array
    {
        return $this->request('DELETE', $name, $args);
    }

    protected function request(string $method, string $name, array $args): array
    {
        $url = $this->getUrl($name);
        $headers = $this->getHeaders();

        switch ($method) {
            case 'GET':
                $query = $this->buildQuery($url, $args);
                $response = $this->client->get("{$url}{$query}", ['headers' => $headers]);

                break;

            case 'POST':
                $response = $this->client->post($url, json_encode($args), ['headers' => $headers]);

                break;

            case 'PUT':
                $response = $this->client->put($url, json_encode($args), ['headers' => $headers]);

                break;

            case 'PATCH':
                $response = $this->client->patch($url, json_encode($args), ['headers' => $headers]);

                break;

            case 'DELETE':
                $query = $this->buildQuery($url, $args);
                $response = $this->client->delete("{$url}{$query}", ['headers' => $headers]);

                break;

            default:
                throw new \Exception("Call to undefined method {$method}");
        }

        try {
            return $this->processResponse($response, $url);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage() . " for {$method} request on {$url}" . ($query ?? ''), $e->getCode());
        }
    }

    protected function buildQuery($url, array $args): string
    {
        $query = parse_url($url)['query'] ?? '';
        $query = ($query ? '&' : '?') . http_build_query($args, '', '&');

        return $query;
    }

    protected function processResponse(Response $response): array
    {
        $encoded = json_decode($response->getBody(), true);
        $success = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && $encoded;

        return [
            'success' => $success,
            'data' => $encoded,
        ];
    }
}
