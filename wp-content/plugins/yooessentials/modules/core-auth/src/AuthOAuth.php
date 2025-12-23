<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth;

class AuthOAuth extends Auth
{
    public function __construct(array $data)
    {
        if ($expiresIn = $data['expiresIn'] ?? null) {
            $data['expiresAt'] = $this->fromExpiresInToAt($expiresIn);
            unset($data['expiresIn']);
        } elseif ($expiresAt = $data['expiresAt'] ?? null) {
            $data['expiresAt'] = self::normalizeDate($expiresAt);
        }

        $this->addEncryptableKeys(['accessToken', 'refreshToken']);

        parent::__construct($data);
    }

    public function id(): string
    {
        return $this->data['id'] ?? uniqid();
    }

    public function setId($id): self
    {
        $this->data['id'] = $id;

        return $this;
    }

    public function userId(): string
    {
        return $this->data['userId'] ?? $this->id();
    }

    public function setUserId(string $userId): self
    {
        $this->data['userId'] = $userId;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->data['username'] = $username;

        return $this;
    }

    public function username(): string
    {
        return $this->data['username'] ?? '';
    }

    public function custom(): string
    {
        return $this->data['custom'] ?? false;
    }

    public function clientId(): string
    {
        return $this->data['clientId'] ?? false;
    }

    public function clientSecret(): string
    {
        return $this->data['clientSecret'] ?? false;
    }

    public function scopes(): array
    {
        return $this->data['scopes'] ?? [];
    }

    /**
     * @param array|string $scopes
     */
    public function setScopes($scopes): self
    {
        if (is_string($scopes) && strpos($scopes, ',') !== false) {
            $scopes = array_filter(array_map('trim', explode(',', $scopes)));
        } elseif (is_string($scopes)) {
            $scopes = array_filter(array_map('trim', explode(' ', $scopes)));
        }

        $this->data['scopes'] = $scopes;

        return $this;
    }

    public function accessToken(): string
    {
        return $this->data['accessToken'] ?? '';
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->data['accessToken'] = $accessToken;

        return $this;
    }

    public function refreshToken(): string
    {
        return $this->data['refreshToken'] ?? '';
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->data['refreshToken'] = $refreshToken;

        return $this;
    }

    public function expiresIn(): int
    {
        $now = new \DateTime();
        $expiresAt = $this->expiresAt();

        return $expiresAt->getTimestamp() - $now->getTimestamp();
    }

    public function setExpiresIn(int $expiresIn): self
    {
        $this->setExpiresAt($this->fromExpiresInToAt($expiresIn));

        return $this;
    }

    public function expiresAt(): \DateTimeInterface
    {
        return $this->data['expiresAt'] ?? new \DateTime();
    }

    /**
     * @param \DateTimeInterface|array|int|string $expiresAt
     */
    public function setExpiresAt($expiresAt): self
    {
        $this->data['expiresAt'] = self::normalizeDate($expiresAt);
        ;

        return $this;
    }

    public function isAccessTokenExpiring(): bool
    {
        $threshold = $this->driver()->accessTokenThreshold();

        if ($threshold && !$this->isAccessTokenExpired() && $this->expiresIn() < $threshold) {
            return true;
        }

        return false;
    }

    public function isAccessTokenExpired(): bool
    {
        if ($this->accessToken() && $this->expiresIn() > 0) {
            return false;
        }

        return true;
    }

    public function renewToken(): self
    {
        if (!$this->isTokenRenewable()) {
            throw new \Exception('No Driver set or Driver does not implement RenewTokenInterface.');
        }

        return $this->driver()->renewToken($this);
    }

    public function isTokenRenewable(): bool
    {
        if ($this->driver() && $this->driver() instanceof RenewTokenInterface) {
            return true;
        }

        return false;
    }

    protected function fromExpiresInToAt(int $expiresIn): \DateTimeInterface
    {
        return (new \DateTime())->modify("$expiresIn seconds");
    }

    /**
     * @param \DateTimeInterface|array|int|string $expiresAt
     */
    protected function normalizeDate($expiresAt): \DateTimeInterface
    {
        if (is_array($expiresAt)) {
            return new \DateTime($expiresAt['date'] ?? null);
        } elseif (is_int($expiresAt)) {
            return (new \DateTime())->setTimestamp($expiresAt);
        } elseif (is_string($expiresAt)) {
            return new \DateTime($expiresAt);
        }

        return $expiresAt;
    }
}
