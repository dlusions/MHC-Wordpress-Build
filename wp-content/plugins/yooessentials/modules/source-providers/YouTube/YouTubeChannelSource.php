<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Api\Google\YouTubeApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class YouTubeChannelSource extends YouTubeSource
{
    public string $account;
    public string $channel;

    protected string $configFile = 'config-channel.json';

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->account = $config['account'] ?? '';
        $this->channel = $config['channel_id'] ?? '';

        return $this;
    }

    public function types(): array
    {
        return [
            new Type\YouTubeVideoType(),
            new Type\YouTubeChannelType(),
            new Type\YouTubePlaylistType(),
            new Type\YouTubeChannelQueryType($this),
            new Type\YouTubeChannelVideoQueryType($this),
            new Type\YouTubeChannelVideosQueryType($this),
            new Type\YouTubeChannelPlaylistQueryType($this),
            new Type\YouTubeChannelPlaylistsQueryType($this),
        ];
    }

    public function api(): YouTubeApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        $auth = app(AuthManager::class)->auth($this->account);

        if (!$auth) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'youtube',
                'error' => 'Missing Auth',
            ]);
        }

        return $this->api = app(YouTubeApiInterface::class)->withAccessToken($auth->accessToken());
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
