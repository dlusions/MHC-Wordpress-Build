<?php
/**
 * @package DJ-SectionsAnywhere
 * @copyright Copyright (C) 2017  DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJContentFilters is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJContentFilters is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJContentFilters. If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace DJExtensions\Yootheme\DJPopup\Listener;


use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;

class BuilderListener
{
    public static function initCustomizer(Config $config, Metadata $metadata)
    {
        $config->addFile('customizer.panels.djpopup-form-config', Path::get('../../config/djpopup-section-config.php'));

        $metadata->set('script:djpopup-customizer', ['src' => '~djpopup_url/modules/builder/assets/js/customizer.min.js', 'defer' => true]);
        $metadata->set('style:djpopup-customizer', ['href' => '~djpopup_url/modules/builder/assets/css/customizer.min.css', 'defer' => true]);
   

    }

    public static function addFormPanel(Config $config, $type)
    {
        // constraint types
        if (!in_array($type['name'], ['section'])) {
            return $type;
        }

        // make sure the main fieldset is set
        if (!Arr::has($type, 'fieldset.default')) {
            return $type;
        }


        $tabs = array_reduce($type['fieldset']['default']['fields'], function ($carry, $v) {
            return array_merge($carry, [$v['title'] ?? null]);
        }, []);



        if (($index = array_search('Settings', $tabs)) === false) {
            return $type;
        }

        $fields = [];


        $fields[] = [
            'name' => 'djpopup',
            'text' => 'DJ-Popup',
            'type' => 'button-panel',
            'panel' => 'djpopup-form-config',
        ];


        // set button right after status field
        Arr::splice($type['fieldset']['default']['fields'][$index]['fields'], 0, 0, $fields);

        return $type;
    }

    public static function initHead(Metadata $metadata)
    {
        $metadata->set('script:djpopup-js', ['src' => Path::get('../../assets/js/djpopup.js'), 'defer' => false]);
        $metadata->set('style:djpopup-css', ['href' => Path::get('../../assets/css/djpopup.css'), 'defer' => false]);

    }
}