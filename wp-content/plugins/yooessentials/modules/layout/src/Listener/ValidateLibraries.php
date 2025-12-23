<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout\Listener;

use YOOtheme\Arr;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Layout\LayoutManager;
use ZOOlanders\YOOessentials\Util;

class ValidateLibraries
{
    public function load($values): array
    {
        $key = LayoutManager::LIBRARIES_CONFIG_KEY;
        $libraries = Arr::get($values, $key, []);

        if (empty($libraries)) {
            return $values;
        }

        $libraries = Util\Arr::removeDuplicates($libraries);

        if (count(Arr::get($values, $key, [])) !== count($libraries)) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'task' => 'load-config',
                'error' => 'Essentials config contains duplicated libraries.',
            ]);
        }

        Arr::set($values, $key, $libraries);

        return $values;
    }
}
