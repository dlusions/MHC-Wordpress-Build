<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Listener;

use YOOtheme\Arr;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Dynamic\Resolvers\GlobalSourceQueryResolver;

class ValidateGlobalQueries
{
    public function load($values): array
    {
        $key = GlobalSourceQueryResolver::GLOBAL_QUERIES_CONFIG_KEY;
        $queries = Arr::get($values, $key, []);

        if (empty($queries)) {
            return $values;
        }

        $queries = Util\Arr::removeDuplicates($queries);

        if (count(Arr::get($values, $key, [])) !== count($queries)) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'task' => 'load-config',
                'error' => 'Essentials config contains duplicated global queries.',
            ]);
        }

        Arr::set($values, $key, $queries);

        return $values;
    }
}
