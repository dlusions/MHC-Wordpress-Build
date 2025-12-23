<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth;

use YOOtheme\Event;
use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Config\ConfigRepositoryInterface;
use ZOOlanders\YOOessentials\Data;

class AuthManager
{
    public const AUTHS_CONFIG_KEY = 'auth.auths';

    /** @var AuthDriver[] */
    protected array $drivers = [];

    protected Config $config;

    private ConfigRepositoryInterface $repository;

    public function __construct(Config $config, ConfigRepositoryInterface $repository)
    {
        $this->config = $config;
        $this->repository = $repository;
    }

    public function setAuths(array $auths): self
    {
        $this->config->set(self::AUTHS_CONFIG_KEY, $auths);

        return $this;
    }

    public function saveAuths(?array $auths = null): self
    {
        if ($auths === null) {
            $auths = $this->authConfigs();
        }

        $auths = $this->createAuths($auths);
        $auths = self::removeDuplicates($auths);

        $auths = array_map(fn (Auth $auth) => $auth->withEncryptedKeys()->toArray(), $auths);

        $this->setAuths($auths);

        $this->repository->save($this->config);

        return $this;
    }

    public function driver(string $driver): ?AuthDriver
    {
        return $this->drivers[$driver] ?? null;
    }

    public function drivers(): array
    {
        return $this->drivers;
    }

    public function addDriver(string $name, AuthDriver $driver): self
    {
        $this->drivers[$name] = $driver;

        return $this;
    }

    public function authConfigs(): array
    {
        return $this->config->get(self::AUTHS_CONFIG_KEY, []);
    }

    public function authConfig(?string $id): ?array
    {
        $key = array_search($id, array_column($this->authConfigs(), 'id'));

        if ($key === false) {
            return null;
        }

        return $this->authConfigs()[$key];
    }

    public function auth(?string $id): ?Auth
    {
        $authConfig = $this->authConfig($id);

        if ($authConfig === null) {
            return null;
        }

        /** @var Auth|AuthOAuth */
        $auth = $this->initAuth($authConfig);

        if (!$auth->driver() || !$auth->driver()->isOAuth() || !$auth->isTokenRenewable()) {
            return $auth;
        }

        if ($auth->isAccessTokenExpiring()) {
            $this->renewAuthToken($auth);

            return $auth;
        }

        // Fail silently, because it may be unsupported by the driver,
        // There could be no refresh token set
        // We could be in a test suite
        if ($auth->isAccessTokenExpired()) {
            $this->renewAuthToken($auth, 'info');

            return $auth;
        }

        return $auth;
    }

    public function renewAuthToken(Auth $auth, string $failureEventType = 'error'): void
    {
        $key = array_search($auth->id(), array_column($this->authConfigs(), 'id'));

        try {
            $auth->renewToken();

            // persist changes
            $auths = $this->authConfigs();
            $auths[$key] = $auth->toArray();
            $this->config->set(AuthManager::AUTHS_CONFIG_KEY, $auths);
            $this->saveAuths();
        } catch (\Throwable $e) {
            Event::emit('yooessentials.' . $failureEventType, [
                'addon' => 'auth',
                'task' => 'renew-auth-token',
                'auth-id' => $auth->id(),
                'auth-driver' => $auth->driver()->name ?? '',
                'expires-at' => $auth->expiresAt(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param string|null $driver
     * @return Auth[]
     */
    public function auths(?string $driver = null): array
    {
        $auths = $this->authConfigs();

        if ($driver) {
            $auths = array_filter($auths, fn (array $auth) => $auth['driver'] === $driver);
        }

        return $this->createAuths($auths);
    }

    public function createAuths(array $data): array
    {
        return array_map(function ($d) {
            if ($d instanceof Auth) {
                return $d;
            }

            if ($d instanceof Data) {
                $d = $d->toArray();
            }

            return $this->initAuth($d);
        }, $data);
    }

    public function initAuth(array $data): Auth
    {
        if ($driver = $this->driver($data['driver'] ?? '')) {
            $auth = $driver->isOAuth() ? new AuthOAuth($data) : new Auth($data);

            $auth->forDriver($driver)->addEncryptableKeys($driver->encryptableKeys());
        } else {
            $auth = new Auth($data);
        }

        return $auth->withDecryptedKeys();
    }

    public static function removeDuplicates(array $auths): array
    {
        $unique = [];

        /** @var Auth $auth */
        foreach ($auths as $auth) {
            $unique["{$auth->driverName()}-{$auth->id}"] = $auth;
        }

        return array_values($unique);
    }
}
