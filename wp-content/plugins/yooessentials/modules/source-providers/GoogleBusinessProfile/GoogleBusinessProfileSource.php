<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Api\Google\GoogleBusinessProfileApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class GoogleBusinessProfileSource extends AbstractSourceType implements HasCacheTimes
{
    public string $account;

    public string $location;

    public string $businessAccount;

    private ?GoogleBusinessProfileApiInterface $api = null;

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->account = $config['account'] ?? '';
        $this->location = $config['location'] ?? '';
        $this->businessAccount = $config['businessAccount'] ?? '';

        return $this;
    }

    public function types(): array
    {
        return [
            new Type\GoogleBusinessProfileLocationType(),
            new Type\GoogleBusinessProfilePeriodType(),
            new Type\GoogleBusinessProfilePostalAddressType(),
            new Type\GoogleBusinessProfileReviewType(),
            new Type\GoogleBusinessProfileReviewReplyType(),
            new Type\GoogleBusinessProfileReviewerType(),
            new Type\GoogleBusinessProfileMediaType(),
            new Type\GoogleBusinessProfileMediaAttributionType(),
            new Type\GoogleBusinessProfileMediaLocationAssociationType(),
            new Type\GoogleBusinessProfilePostType(),
            new Type\GoogleBusinessProfilePostOfferType(),
            new Type\GoogleBusinessProfileLocationQuery($this),
            new Type\GoogleBusinessProfileReviewQuery($this),
            new Type\GoogleBusinessProfileReviewsQuery($this),
            new Type\GoogleBusinessProfileMediaQuery($this),
            new Type\GoogleBusinessProfilePostsQuery($this),
        ];
    }

    public function defaultName(): string
    {
        return 'Google Business Profile';
    }

    public function auth(): ?AuthOAuth
    {
        return app(AuthManager::class)->auth($this->account);
    }

    public function api(): GoogleBusinessProfileApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        if (!$this->auth()) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'google-business-profile',
                'error' => 'Missing Auth',
            ]);
        }

        return $this->api = app(GoogleBusinessProfileApiInterface::class)->withAccessToken($this->auth()->accessToken());
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
