<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access;

use YOOtheme\Url;

abstract class LegacyAccessRule implements LegacyRuleInterface
{
    public function icon(): string
    {
        $icon = str_replace('yooessentials_access_', '', $this->namespace());

        return Url::to("~yooessentials_url/modules/access/assets/icons/$icon.svg");
    }

    public function docs(): string
    {
        return '';
    }

    public function resolveProps(object $props, object $node): object
    {
        return $props;
    }

    protected static function parseTextareaList($content): array
    {
        if (is_string($content)) {
            return array_map(fn ($value) => trim($value), explode(',', str_replace(["\r", "\n"], ['', ','], $content)));
        }

        return $content;
    }
}
