<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Config;

use function YOOtheme\app;
use YOOtheme\Config as Yooconfig;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Str;

class ConfigListener
{
    public static function initCustomizer(Yooconfig $yooconfig, Config $config)
    {
        // expose the config in the customizer, used as initial value
        $yooconfig->add('yooessentials.customizer.config', $config->toArray());
    }

    public static function cleanYooConfig($values)
    {
        unset($values['yooessentials']);

        return $values;
    }

    /**
     * Handles JSON requests.
     *
     * @param Request  $request
     * @param callable $next
     *
     * @return Response
     */
    public static function loadConfigFromRequest($request, callable $next): Response
    {
        $requestConfig = self::fromRequest($request);

        /** @var Config $config */
        $config = app(Config::class);
        if ($requestConfig !== null) {
            $config->replace($requestConfig);
        }

        return $next($request);
    }

    public static function fromRequest(Request $request): ?array
    {
        // if config in yooessentials request
        if (Str::startsWith($request('p'), 'yooessentials') && ($values = $request('config'))) {
            return $values;
        }

        return app(ConfigRepositoryInterface::class)->fromRequest($request);
    }
}
