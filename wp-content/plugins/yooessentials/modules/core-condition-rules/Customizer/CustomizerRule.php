<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Customizer;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Condition\ConditionRule;

class CustomizerRule extends ConditionRule
{
    public function resolveProps(object $props, object $node): object
    {
        return $props;
    }

    public function resolve($props, $node): bool
    {
        return app()->config->get('app.isCustomizer', false);
    }
}
