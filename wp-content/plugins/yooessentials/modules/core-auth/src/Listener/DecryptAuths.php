<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Listener;

use YOOtheme\Arr;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class DecryptAuths
{
    public AuthManager $manager;

    public function __construct(AuthManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle($values): array
    {
        $key = AuthManager::AUTHS_CONFIG_KEY;
        $auths = Arr::get($values, $key, []);

        if (empty($auths)) {
            return $values;
        }

        try {
            $auths = $this->manager->createAuths($auths);

            $auths = array_map(fn (Auth $auth) => $auth->withDecryptedKeys()->toArray(), $auths);

            Arr::set($values, $key, $auths);

            return $values;
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'auth',
                'task' => 'decrypt-config-auths',
                'error' => $e->getMessage(),
            ]);

            return $values;
        }
    }
}
