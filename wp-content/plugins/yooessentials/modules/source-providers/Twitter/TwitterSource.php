<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Twitter;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\Twitter\TwitterApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class TwitterSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    public const TWEETS_DEFAULT_LIMIT = 20;

    private ?TwitterApiInterface $api = null;

    public function types(): array
    {
        if (!$this->auth()) {
            return [];
        }

        return [
            new Type\TwitterUserType(),
            new Type\TwitterTweetType(),
            new Type\TwitterMediaPhotoType(),
            new Type\TwitterMediaVideoType(),
            new Type\TwitterUserQueryType($this),
            new Type\TwitterMyTweetsQueryType($this),
        ];
    }

    public function account(): ?string
    {
        return $this->config()['account'] ?? null;
    }

    public function auth(): ?AuthOAuth
    {
        return app(AuthManager::class)->auth($this->account());
    }

    public function api(): TwitterApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        return $this->api = app(TwitterApiInterface::class)->withAccessToken($this->auth()->accessToken());
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
