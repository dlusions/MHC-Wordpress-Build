<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access\Listener;

use YOOtheme\Arr;
use YOOtheme\Config;

class ExtendBuilderType
{
    public Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function handle($type)
    {
        if (!$this->config->get('app.isCustomizer')) {
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

        $accessField = [
            'name' => '_yooessentials_access',
            'label' => 'Access Condition',
            'type' => 'yooessentials-condition-button',
            'heading' => true,
            'panel' => 'yooessentials-access-condition',
            'description' => 'Compose a condition that will determine the display of this element.',
        ];

        // get fields
        $fields = $type['fieldset']['default']['fields'][$index]['fields'] ?? null;

        if (!$fields || !is_array($fields)) {
            return $type;
        }

        // set button after status or after name
        Arr::splice($fields, ($fields[1] ?? '') === 'status' ? 2 : 1, 0, [$accessField]);
        $type['fieldset']['default']['fields'][$index]['fields'] = $fields;

        // update source condition field description
        if (
            ($path = 'fields.source.fields._sourceCondition.fields._sourceConditionProp.description') and
            ($desc = Arr::get($type, $path))
        ) {
            Arr::set($type, $path, $desc . ' For a more advanced workflow use <b>Access Condition</b> instead.');
        }

        return $type;
    }
}
