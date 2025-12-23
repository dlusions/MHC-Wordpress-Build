<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use ZOOlanders\YOOessentials\Session as SessionInterface;

class Session implements SessionInterface
{
    public const SESSION_KEY = 'yooessentials_session';
    protected $started = false;
    protected $closed = false;
    protected $data = [];

    /**
     * Checks if a key exists.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Gets a value.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    /**
     * Sets a value.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Unsets a key.
     *
     * @param string $name
     */
    public function clear($name)
    {
        unset($this->data[$name]);
    }

    public function start()
    {
        if (defined('WP_CLI') && WP_CLI === true) {
            return;
        }

        if (defined('DOING_CRON') && DOING_CRON === true) {
            return;
        }

        if ($this->started) {
            return;
        }

        // Register our function as shutdown method, so we can manipulate it
        register_shutdown_function([$this, 'save']);

        // Disable the cache limiter
        session_cache_limiter('none');

        // try to use the native API
        if (PHP_SESSION_ACTIVE === session_status()) {
            return;
        }

        // If we are using cookies (default true) and headers have already been started (early output),
        if (ini_get('session.use_cookies') && headers_sent($file, $line)) {
            return;
        }

        // Ok to try and start the session
        if (!session_start()) {
            return;
        }

        // Mark ourselves as started
        $this->started = true;

        $data = $_SESSION[self::SESSION_KEY];
        $data = base64_decode($data);
        $this->data = unserialize($data);
    }

    public function close()
    {
        // Need to destroy any existing sessions started with session.auto_start
        if (PHP_SESSION_ACTIVE === session_status()) {
            session_unset();
            session_destroy();
        }

        $this->closed = true;
        $this->started = false;
    }

    public function save()
    {
        // Verify if the session is active
        if (PHP_SESSION_ACTIVE === session_status()) {
            // Before storing it, let's serialize and encode the Registry object
            $_SESSION[self::SESSION_KEY] = base64_encode(serialize($this->data));

            session_write_close();

            $this->closed = true;
            $this->started = false;
        }
    }
}
