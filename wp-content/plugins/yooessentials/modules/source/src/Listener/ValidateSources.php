<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Listener;

use YOOtheme\Arr;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Source\SourceService;
use ZOOlanders\YOOessentials\Util;

class ValidateSources
{
    public function load($values): array
    {
        $key = SourceService::SOURCES_CONFIG_KEY;
        $sources = Arr::get($values, $key, []);

        if (empty($sources)) {
            return $values;
        }

        $sources = Util\Arr::removeDuplicates($sources);

        if (count(Arr::get($values, $key, [])) !== count($sources)) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'task' => 'load-config',
                'error' => 'Essentials config contains duplicated sources.',
            ]);
        }

        Arr::set($values, $key, $sources);

        return $values;
    }
}
