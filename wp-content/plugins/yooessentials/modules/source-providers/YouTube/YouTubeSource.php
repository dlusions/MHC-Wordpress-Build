<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Api\Google\YouTubeApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class YouTubeSource extends AbstractSourceType implements HasCacheTimes
{
    public string $apiKey;

    protected ?YouTubeApiInterface $api = null;

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->apiKey = $config['api_key'] ?? '';

        return $this;
    }

    public function types(): array
    {
        return [
            new Type\YouTubeVideoType(),
            new Type\YouTubeVideoQueryType($this),
            new Type\YouTubeVideosQueryType($this)
        ];
    }

    public function api(): YouTubeApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        $auth = app(AuthManager::class)->auth($this->apiKey);

        if (!isset($auth->key)) {
            throw new \Exception('Missing API Key');
        }

        return $this->api = app(YouTubeApiInterface::class)->withApiKey($auth->key);
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        $auth = app(AuthManager::class)->auth($this->apiKey);
        if (!$auth) {
            return HasCacheTimes::MIN_CACHE_TIME;
        }

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
