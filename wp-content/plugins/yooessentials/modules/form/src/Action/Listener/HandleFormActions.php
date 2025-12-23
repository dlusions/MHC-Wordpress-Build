<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Listener;

use YOOtheme\Middleware;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class HandleFormActions
{
    private static $registered = false;

    /**
     * @param FormSubmissionResponse $response
     * @param callable $next
     */
    public static function process($response, $next)
    {
        if (self::$registered) {
            return $next($response);
        }

        $form = $response->submission()->form();

        if ($form->hasExternalActionUrl()) {
            return $next($response);
        }

        $middleware = new Middleware(fn () => $next($response), $form->actions());

        self::$registered = true;

        /** @var FormSubmissionResponse $response */
        return $middleware($response);
    }
}
