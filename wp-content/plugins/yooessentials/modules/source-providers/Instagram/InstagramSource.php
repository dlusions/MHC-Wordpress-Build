<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Api\Instagram\InstagramApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class InstagramSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    public const MEDIA_LIMIT_DEFAULT = 20;
    public const MEDIA_CACHE_TIME_DEFAULT = 3600;

    protected ?InstagramApiInterface $api = null;

    public function types(): array
    {
        if (!$this->auth()) {
            return [];
        }

        $types = [
            new Type\InstagramUserType(),
            new Type\InstagramMediaType(),
            new Type\InstagramAlbumMediaType(),
            new Type\InstagramUserQueryType($this),
            new Type\InstagramMediaQueryType($this),
            new Type\InstagramMediaSingleQueryType($this),
        ];

        if ($this->auth()->driverName() === 'facebook') {
            $types[] = new Type\InstagramHashtaggedMediaQueryType($this);
        }

        return $types;
    }

    public function account(): ?string
    {
        return $this->config()['account'] ?? null;
    }

    public function auth(): ?AuthOAuth
    {
        $auth = app(AuthManager::class)->auth($this->account());
        if (!$auth instanceof AuthOAuth) {
            return null;
        }

        return $auth;
    }

    public function pageId(): ?string
    {
        return $this->config()['page_id'] ?? $this->auth()->userId() ?? null;
    }

    public function api(): InstagramApiInterface
    {
        if ($this->api !== null) {
            return $this->api;
        }

        $this->api = app(InstagramApiInterface::class)
            ->withAccessToken($this->auth()->accessToken())
            ->forDriver($this->auth()->driverName());

        return $this->api;
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        if (!($auth = $this->auth())) {
            return HasCacheTimes::MIN_CACHE_TIME;
        }

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
