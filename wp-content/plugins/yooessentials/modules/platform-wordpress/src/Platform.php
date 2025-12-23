<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use YOOtheme\Application;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Session;

class Platform
{
    const NONCE_NAME = '_wpnonce_yooessentials';

    /**
     * Init Session when needed
     *
     * @param Application $app
     */
    public static function initSession(Session $session)
    {
        $session->start();
    }

    /**
     * Handle application routes.
     *
     * @param Application $app
     */
    public static function handleRoute(Application $app)
    {
        if (isset($__GET['p']) && Str::startsWith($__GET['p'], '/yooessentials/')) {
            $app->run();
            exit();
        }
    }

    /**
     * Prints the HTML form token used for CSRF validation
     */
    public static function printCsrfFormToken()
    {
        // This is the same of the wp_nonce_field() method, without the `id=""` part
        return '<input type="hidden" name="' . self::NONCE_NAME . '" value="' . wp_create_nonce() . '" />';
    }
}
