<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\TikTok;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\TikTok\TikTokApiInterface;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class TikTokSource extends AbstractSourceType implements HasCacheTimes
{
    public const VIDEO_LIMIT_DEFAULT = 20;
    public const VIDEO_CACHE_TIME_DEFAULT = 3600;

    public string $account;

    private ?TikTokApiInterface $api = null;

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->account = $config['account'] ?? '';

        return $this;
    }

    public function types(): array
    {
        return [new Type\TikTokVideoType(), new Type\TikTokMyVideosQueryType($this)];
    }

    public function api(): TikTokApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        try {
            $auth = app(AuthManager::class)->auth($this->account);

            return $this->api = app(TikTokApiInterface::class)->withAccessToken($auth->accessToken());
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'tiktok',
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return app(TikTokApiInterface::class);
        }
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        $auth = app(AuthManager::class)->auth($this->account);
        if (!$auth) {
            return HasCacheTimes::MIN_CACHE_TIME;
        }

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
