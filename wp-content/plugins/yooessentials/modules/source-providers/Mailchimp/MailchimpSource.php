<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;

class MailchimpSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    public const RECORDS_OFFSET = 0;
    public const RECORDS_LIMIT = 20;

    public string $auth;
    public string $list;

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->auth = $config['auth'] ?? '';
        $this->list = $config['list'] ?? '';

        return $this;
    }

    public function types(): array
    {
        if (!$this->getAuth()) {
            return [];
        }

        return [
            new Type\MailchimpListType(),
            new Type\MailchimpListMemberType(),
            new Type\MailchimpListInterestType(),
            new Type\MailchimpListQueryType($this),
            new Type\MailchimpListMemberQueryType($this),
            new Type\MailchimpListInterestsQueryType($this)
        ];
    }

    public function defaultName(): string
    {
        return 'Mailchimp';
    }

    public function getAuth(): ?Auth
    {
        if (!$this->auth) {
            throw new \Exception('Auth must be specified.');
        }

        return app(AuthManager::class)->auth($this->auth);
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

        if ($auth->driver === 'mailchimp') {
            return 0;
        }

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
