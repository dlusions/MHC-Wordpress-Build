<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

use function YOOtheme\app;

abstract class Feature
{
    // public const VIEW_ADD_TRANSFORM = 'VIEW_ADD_TRANSFORM';

    private const FEATURES = [
        // self::VIEW_ADD_TRANSFORM => [self::class, 'doesViewSupportTransforms'],
    ];

    public static function canUse(string $feature): bool
    {
        if (!isset(self::FEATURES[$feature])) {
            return false;
        }

        if (is_callable(self::FEATURES[$feature])) {
            return call_user_func(self::FEATURES[$feature]);
        }

        $ytpVersion = app()->config->get('theme.version', '');
        if ($ytpVersion && is_string(self::FEATURES[$feature])) {
            return version_compare($ytpVersion, self::FEATURES[$feature], '>=');
        }

        return false;
    }

    public static function cannotUse(string $feature): bool
    {
        return !self::canUse($feature);
    }

    // protected static function doesViewSupportTransforms(): bool
    // {
    //     $r = new \ReflectionClass(HtmlHelper::class);

    //     return $r->hasMethod('addTransform') && $r->getMethod('addTransform')->isPublic();
    // }
}
