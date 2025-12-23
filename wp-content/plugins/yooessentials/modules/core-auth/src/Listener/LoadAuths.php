<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Listener;

use YOOtheme\Event;
use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class LoadAuths
{
    public Config $config;
    public AuthManager $manager;

    public function __construct(Config $config, AuthManager $manager)
    {
        $this->config = $config;
        $this->manager = $manager;
    }

    public function handle(): void
    {
        $key = AuthManager::AUTHS_CONFIG_KEY;

        try {
            $auths = $this->manager->createAuths($this->config->get($key, []));

            $auths = array_map(fn (Auth $auth) => $auth->withDecryptedKeys()->toArray(), $auths);

            $this->config->set($key, $auths);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'auth',
                'task' => 'load-config',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
