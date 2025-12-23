<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

class OptimizeTransform
{
    private const FORM_DATA_KEY = 'yooessentials_form';

    /**
     * Transform callback.
     *
     * @param object $node
     * @param array  $params
     */
    public function __invoke($node, array $params)
    {
        $isFormArea = (bool) ($node->props[self::FORM_DATA_KEY]->state ?? false);
        $hasFormAreaData = (bool) ($node->props[self::FORM_DATA_KEY] ?? ($node->formid ?? false));

        if (!$isFormArea && $hasFormAreaData) {
            unset($node->formid);
            unset($node->props[self::FORM_DATA_KEY]);
        }
    }
}
