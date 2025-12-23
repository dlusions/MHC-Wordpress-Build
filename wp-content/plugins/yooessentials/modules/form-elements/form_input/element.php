<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use ZOOlanders\YOOessentials\Util;

return [
    'transforms' => [
        'render' => function (object $node, array $params) {
            $node->propsControl = Util\Prop::filterByPrefix($node->props, 'control_');

            $fieldset = Util\Node::findClosestType($params, 'yooessentials_form_fieldset');

            if ($fieldset && !$fieldset->props['fields_show_label']) {
                $node->props['show_label'] = false;
            }
        },
    ],
];
