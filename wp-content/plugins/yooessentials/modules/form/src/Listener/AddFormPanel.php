<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Listener;

use function YOOtheme\app;
use YOOtheme\Arr;

class AddFormPanel
{
    public function handle($type)
    {
        // constraint types
        if (!isset($type['name']) || !in_array($type['name'], ['section', 'column', 'fragment'])) {
            return $type;
        }

        // make sure the main fieldset is set
        if (!Arr::has($type, 'fieldset.default')) {
            return $type;
        }

        $tabs = array_reduce(
            $type['fieldset']['default']['fields'],
            fn ($carry, $v) => array_merge($carry, [$v['title'] ?? null]),
            []
        );

        if (($index = array_search('Advanced', $tabs)) === false) {
            return $type;
        }

        if ($type['name'] === 'fragment') {
            $fields = app()->config->get('yooessentials.form.fields.sublayout_form_area', []);
        } else {
            $fields = app()->config->get('yooessentials.form.fields.form_area', []);
        }

        // set right after status field
        Arr::splice($type['fieldset']['default']['fields'][$index]['fields'], 2, 0, $fields);

        return $type;
    }
}
