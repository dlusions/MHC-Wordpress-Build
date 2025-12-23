<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress\Listener;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Wordpress\Platform;

class CsrfTokenMiddleware
{
    /**
     * Handles CSRF token from request.
     *
     * @param Request $request
     * @param callable $next
     *
     * @return Response
     */
    public function handle($request, callable $next)
    {
        $token = $request->getParam(Platform::NONCE_NAME);

        if ($token) {
            $request = $request->withHeader('X-XSRF-Token', $token);
        }

        return $next($request);
    }
}
