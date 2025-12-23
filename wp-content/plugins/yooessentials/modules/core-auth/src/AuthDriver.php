<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth;

use ZOOlanders\YOOessentials\Data;

class AuthDriver extends Data
{
    public const TYPE_OAUTH = 'oauth';
    public const TYPE_APIKEY = 'apikey';
    public const TYPE_AUTH = 'auth';

    public function is(string $driver): bool
    {
        return $this->name() === $driver;
    }

    public function isAuth(): bool
    {
        return $this->type() === self::TYPE_AUTH;
    }

    public function isOAuth(): bool
    {
        return $this->type() === self::TYPE_OAUTH;
    }

    public function fields(): array
    {
        return $this->extractFields($this->data ?? []);
    }

    public function type(): string
    {
        return $this->data['type'] ?? self::TYPE_APIKEY;
    }

    public function name(): string
    {
        return $this->data['name'] ?? '';
    }

    public function title(): string
    {
        return $this->data['title'] ?? $this->name();
    }

    public function endpoints(): array
    {
        return $this->data['endpoints'] ?? [];
    }

    public function endpoint(string $name): ?string
    {
        return $this->endpoints()[$name] ?? null;
    }

    public function accessTokenThreshold(): ?int
    {
        $threshold = $this->data['accessTokenThreshold'] ?? false;

        if (!$threshold) {
            return null;
        }

        $threshold = (new \DateTime())->modify($threshold);

        return $threshold->getTimestamp() - (new \DateTime())->getTimestamp();
    }

    public function encryptableKeys(): array
    {
        return array_keys(
            array_filter($this->fields(), fn (array $field) => $field['encrypt'] ?? false)
        );
    }

    protected function extractFields(array $data): array
    {
        $fields = array_filter($data['fields'] ?? []);

        foreach ($fields as $field) {
            $fields = array_merge($fields, $this->extractFields($field));
        }

        return $fields;
    }
}
