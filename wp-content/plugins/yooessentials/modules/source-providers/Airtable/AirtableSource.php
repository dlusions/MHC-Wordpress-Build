<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Airtable;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Api\Airtable\AirtableApiInterface;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;

class AirtableSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    use CachesResolvedSourceData;

    public const RECORDS_OFFSET = 0;
    public const RECORDS_LIMIT = 20;

    public string $auth;
    public string $base;
    public string $table;

    private ?AirtableApiInterface $api = null;

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->auth = $config['auth'] ?? '';
        $this->base = $config['base'] ?? '';
        $this->table = $config['table'] ?? '';

        return $this;
    }

    public function types(): array
    {
        if (!$this->getAuth()) {
            return [];
        }

        $recordType = new Type\AirtableRecordType($this);

        return [
            $recordType,
            new Type\AirtableUserType(),
            new Type\AirtableAttachmentType(),
            new Type\AirtableRecordQueryType($this, $recordType),
            new Type\AirtableRecordsQueryType($this, $recordType)
        ];
    }

    public static function getCacheKey(): string
    {
        return 'airtable-source';
    }

    public function defaultName(): string
    {
        return 'Airtable';
    }

    public function getAuth(): ?Auth
    {
        if (!$this->auth) {
            throw new \Exception('Auth must be specified.');
        }

        return app(AuthManager::class)->auth($this->auth);
    }

    public function tableFields(): array
    {
        return self::resolveFromCache($this, [], fn () => $this->api()->getTable($this->base, $this->table)['fields'] ?? []);
    }

    public function getTable(?string $arg = null)
    {
        $table = self::resolveFromCache($this, [], fn () => $this->api()->getTable($this->base, $this->table));

        if ($arg) {
            return $table[$arg] ?? null;
        }

        return $table;
    }

    public function api(): AirtableApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        $auth = $this->getAuth();

        if (!$auth) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'airtable',
                'error' => 'Missing Auth',
            ]);
        }

        return $this->api = app(AirtableApiInterface::class)->withAccessToken($auth->accessToken);
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        if (!($auth = $this->getAuth())) {
            return HasCacheTimes::MIN_CACHE_TIME;
        }

        if ($auth->driver === 'airtable-access-token') {
            return 0;
        }

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
