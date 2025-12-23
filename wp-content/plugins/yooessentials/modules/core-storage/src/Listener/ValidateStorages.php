<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage\Listener;

use YOOtheme\Arr;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Storage\StorageService;
use ZOOlanders\YOOessentials\Util;

class ValidateStorages
{
    public function load($values): array
    {
        $key = StorageService::STORAGES_CONFIG_KEY;
        $storages = Arr::get($values, $key, []);

        if (empty($storages)) {
            return $values;
        }

        $storages = Util\Arr::removeDuplicates($storages);

        if (count(Arr::get($values, $key, [])) !== count($storages)) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'task' => 'load-config',
                'error' => 'Essentials config contains duplicated storages.',
            ]);
        }

        Arr::set($values, $key, $storages);

        return $values;
    }
}
