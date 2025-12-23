<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Webhook;

use RuntimeException;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use function YOOtheme\app;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\HttpClientInterface;
use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;

class WebhookAction extends StandardAction
{
    public const NAME = 'webhook';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();

        try {
            $this->request($config);
        } catch (RuntimeException $e) {
            throw ActionConfigurationException::create($this, $e->getMessage(), $e);
        }

        return $next(
            $response->withDataLog([
                self::NAME => [
                    'config' => $config,
                ],
            ])
        );
    }

    protected function request(object $config): Response
    {
        /** @var HttpClientInterface $client */
        $client = app(HttpClientInterface::class);

        $data = self::getData($config);
        $headers = self::getHeaders($config);
        $method = $config->method;

        switch ($method) {
            case 'get':
            case 'delete':
                $query = self::getQuery($config, $data);
                $response = $client->$method("{$config->url}{$query}", ['headers' => $headers]);

                break;

            case 'put':
            case 'post':
            case 'patch':
                $response = $client->$method($config->url, $data, ['headers' => $headers]);

                break;

            default:
                throw ActionConfigurationException::create($this, "Unknown method {$method}");
        }

        $body = $this->parseResponseBody($response);
        $success = $this->isSuccessfulResponse($response, $body);

        if (!$success) {
            $message = $this->getErrorMessage($response, $body);

            throw ActionRuntimeException::create($this, $message);
        }

        return $response;
    }

    protected function getQuery(object $config, array $data): string
    {
        $query = parse_url($config->url, PHP_URL_QUERY) ?: '';

        // convert query string to array
        parse_str($query, $query);

        // merge query with data
        $query = array_merge($query, $data);

        return http_build_query($query, '', '&');
    }

    protected function getData(object $config)
    {
        if ($config->method === 'get' || $config->format === 'form-data') {
            $data = array_column($config->data, 'props');

            return array_column($data, 'value', 'key');
        }

        return $config->body;
    }

    protected function getHeaders(object $config): array
    {
        $headers = [];

        switch ($config->format) {
            case 'json':
                $headers['Content-Type'] = 'application/json';

                break;

            case 'xml':
                $headers['Content-Type'] = 'application/xml';

                break;

            case 'html':
                $headers['Content-Type'] = 'text/html';

                break;

            case 'form-data':
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';

                break;
        }

        return $headers;
    }

    /**
     * Parse the response body, handling various content types and edge cases
     */
    protected function parseResponseBody(Response $response)
    {
        $body = $response->getBody()->getContents();

        // Handle empty body
        if (empty($body)) {
            return null;
        }

        // Try to decode as JSON
        $decoded = json_decode($body, true);

        // If JSON decoding failed, return the raw body
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $body;
        }

        if (!is_string($decoded)) {
            return $decoded;
        }

        // Handle double encoding case
        $doubleDecoded = json_decode($decoded, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $doubleDecoded;
        }

        return $decoded;
    }

    /**
     * Determine if the response should be considered successful
     */
    protected function isSuccessfulResponse(Response $response, $body): bool
    {
        $statusCode = $response->getStatusCode();

        // First check: HTTP status code must be in 2xx range
        if ($statusCode < 200 || $statusCode > 299) {
            return false;
        }

        return true;
    }

    /**
     * Extract a meaningful error message from the response
     */
    protected function getErrorMessage(Response $response, $body): string
    {
        // Try to get error message from response body
        if (is_array($body)) {
            // Common error message fields
            $errorFields = ['error', 'message', 'error_description', 'detail', 'details'];

            foreach ($errorFields as $field) {
                if (isset($body[$field]) && !empty($body[$field])) {
                    if (is_array($body[$field])) {
                        // Handle array of errors
                        return implode('; ', array_filter($body[$field]));
                    }

                    return (string) $body[$field];
                }
            }

            // If errors array exists, try to extract messages
            if (isset($body['errors']) && is_array($body['errors'])) {
                $errorMessages = [];
                foreach ($body['errors'] as $error) {
                    if (is_string($error)) {
                        $errorMessages[] = $error;
                    } elseif (is_array($error) && isset($error['message'])) {
                        $errorMessages[] = $error['message'];
                    }
                }
                if (!empty($errorMessages)) {
                    return implode('; ', $errorMessages);
                }
            }
        }

        // Fall back to HTTP reason phrase or generic message
        $reasonPhrase = $response->getReasonPhrase();
        if (!empty($reasonPhrase)) {
            return "Webhook request failed: {$reasonPhrase} (HTTP {$response->getStatusCode()})";
        }

        return "Webhook request failed with HTTP status {$response->getStatusCode()}";
    }
}
