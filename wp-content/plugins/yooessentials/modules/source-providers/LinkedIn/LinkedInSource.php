<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\LinkedIn\LinkedInApiInterface;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class LinkedInSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    use HasApiRequest;

    public const VIDEO_LIMIT_DEFAULT = 20;
    public const VIDEO_CACHE_TIME_DEFAULT = 3600;

    public function types(): array
    {
        return [
            new Type\LinkedInPostType(),
            new Type\LinkedInPostMediaType(),
            new Type\LinkedInPostArticleType(),
            new Type\LinkedInOrganizationType(),
            new Type\LinkedInOrganizationPostQueryType($this),
            new Type\LinkedInOrganizationPostsQueryType($this),
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

    // public function api(): LinkedInApiInterface
    // {
    //     if ($this->api) {
    //         return $this->api;
    //     }

    //     try {
    //         return $this->api = app(LinkedInApiInterface::class)->withAccessToken($this->auth()->accessToken);
    //     } catch (\Exception $e) {
    //         Event::emit('yooessentials.error', [
    //             'addon' => 'source',
    //             'provider' => 'linkedin',
    //             'error' => $e->getMessage(),
    //             'exception' => $e,
    //         ]);

    //         return app(LinkedInApiInterface::class);
    //     }
    // }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        $auth = $this->auth();

        if (!$auth) {
            return HasCacheTimes::MIN_CACHE_TIME;
        }

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
