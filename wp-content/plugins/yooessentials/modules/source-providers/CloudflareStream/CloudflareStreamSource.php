<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\Cloudflare\CloudflareStreamApi;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class CloudflareStreamSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    protected ?CloudflareStreamApi $api = null;

    protected ?Auth $signingKey = null;

    public function types(): array
    {
        return [
            new Type\CloudflareStreamVideoType(),
            new Type\CloudlfareStreamVideoQueryType($this),
            new Type\CloudlfareStreamVideosQueryType($this),
        ];
    }

    public function uid(): ?string
    {
        return $this->config()['uid'] ?? null;
    }

    public function auth(): ?Auth
    {
        /** @var AuthManager $authManager */
        $authManager = app(AuthManager::class);

        $token = $this->config()['token'] ?? '';

        return $authManager->auth($token);
    }

    public function signingKey(): ?Auth
    {
        if ($this->signingKey) {
            return $this->signingKey;
        }

        /** @var AuthManager $authManager */
        $authManager = app(AuthManager::class);

        $key = $this->config()['signing_key'] ?? '';

        return $this->signingKey = $authManager->auth($key);
    }

    public function api(): CloudflareStreamApi
    {
        if ($this->api) {
            return $this->api;
        }

        $account = $this->config()['account'] ?? null;

        return $this->api = app(CloudflareStreamApi::class)
            ->withAccount($account)
            ->withAccessToken($this->auth()->accessToken);
    }

    public function signStream(array &$stream)
    {
        if (!($stream['requireSignedURLs'] ?? false)) {
            return;
        }

        if ($this->signingKey()) {
            $stream['uid'] = self::signToken($stream['uid'], $this->signingKey()->toArray());
        }
    }

    /**
     * Signs a url token for the stream reproduction
     *
     * @param string $uid The stream uid.
     * @param array $key The key id and pem used for the signing.
     * @param string $exp Expiration; a unix epoch timestamp after which the token will not be accepted.
     * @param string $nbf notBefore; a unix epoch timestamp before which the token will not be accepted.
     *
     * https://dev.to/robdwaller/how-to-create-a-json-web-token-using-php-3gml
     * https://developers.cloudflare.com/stream/viewing-videos/securing-your-stream#creating-a-signing-key
     *
     */
    protected static function signToken(string $uid, array $key, ?string $exp = null, ?string $nbf = null)
    {
        $privateKey = base64_decode($key['pem']);

        $header = ['alg' => 'RS256', 'kid' => $key['id']];
        $payload = ['sub' => $uid, 'kid' => $key['id']];

        if ($exp) {
            $payload['exp'] = $exp;
        }

        if ($nbf) {
            $payload['nbf'] = $nbf;
        }

        $encodedHeader = self::base64Url(json_encode($header));
        $encodedPayload = self::base64Url(json_encode($payload));

        openssl_sign("$encodedHeader.$encodedPayload", $signature, $privateKey, 'RSA-SHA256');

        $encodedSignature = self::base64Url($signature);

        return "$encodedHeader.$encodedPayload.$encodedSignature";
    }

    protected static function base64Url(string $data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        return 0;
    }
}
