<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml;

use YOOtheme\File;
use YOOtheme\Http\Response;
use YOOtheme\Path;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\HttpClientInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\ItemInterface;

class XmlService
{
    private HttpClientInterface $client;
    private CacheInterface $cache;

    private int $cacheTime = 0;
    private ?string $cacheKey = null;

    public function __construct(HttpClientInterface $client, CacheInterface  $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function withCache(int $time, ?string $cacheKey = null): self
    {
        $this->cacheTime = $time;
        $this->cacheKey = $cacheKey;

        return $this;
    }

    public function loadFromFile(string $file): \SimpleXMLElement
    {
        if ($file and !Path::isAbsolute($file) && !Str::startsWith($file, '~')) {
            $file = "~/$file";
        }

        if (!File::exists($file)) {
            throw new \Exception("File Not Found at '{$file}'");
        }

        return new \SimpleXMLElement(File::getContents($file), LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NOCDATA);
    }

    public function loadFromUrl(string $url, ?Auth $auth = null, ?int $cacheTime = null): \SimpleXMLElement
    {
        $cacheKey = $this->cacheKey ?? 'xml-service-' . sha1($url . ($auth ? json_encode($auth->toArray()) : ''));
        $cacheTime ??= $this->cacheTime ?? 0;

        if ($cacheTime <= 0) {
            $this->cache->delete($cacheKey);
        }

        $body = $this->cache->get($cacheKey, function (ItemInterface $item) use ($auth, $url) {
            $item->expiresAfter($this->cacheTime);

            return $this->performLoadFromUrl($auth, $url);
        });

        return new \SimpleXMLElement($body, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NOCDATA);
    }

    private function performLoadFromUrl(?Auth $auth, string $url): string
    {
        $authDriver = $auth ? $auth->driverName() : null;
        $options = [];

        switch ($authDriver) {
            case 'basic-auth':
                $options = [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode($auth->username . ':' . $auth->password),
                    ],
                ];

                break;

            case 'api-key':
                $param = $auth->field_name ?? 'key';

                if ($auth->mode === 'header') {
                    $options = [
                        'headers' => [
                            $param => $auth->key,
                        ],
                    ];

                    break;
                }

                $url = http_build_url($url, [
                    'query' => $param . '=' . $auth->key
                ], HTTP_URL_JOIN_QUERY);

                break;
        }

        /** @var Response $response */
        $response = $this->client->get($url, $options);

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new \Exception('Cannot load Feed: ' . (string) $response->getBody());
        }

        $body = (string) $response->getBody();

        return $body;
    }
}
