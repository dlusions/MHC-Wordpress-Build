<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access;

use function Yootheme\app;
use ZOOlanders\YOOessentials\Debug\Logger;
use ZOOlanders\YOOessentials\Condition\ConditionManager;
use ZOOlanders\YOOessentials\Condition\ConditionResolver;

class AccessTransform
{
    protected ConditionResolver $resolver;

    public function __invoke(object $node, array $params = []): bool
    {
        $query = $node->props['yooessentials_access_mode'] ?? null;
        $conditions = $node->props['yooessentials_access_conditions'] ?? [];

        if (!$conditions) {
            return true;
        }

        // coerce to object
        $conditions = array_map(fn ($condition) => (object) $condition, $conditions);

        if ($query === ConditionManager::MODE_CUSTOM) {
            $query = $node->props['yooessentials_access_mode_query'] ?? null;
        }

        if (!$query) {
            $query = ConditionManager::MODE_AND;
        }

        /** @var ConditionResolver $resolver */
        $resolver = (new ConditionResolver($conditions))->withQuery($query);

        // node id, fallback to parents
        $id = $node->attrs['data-id'] ?? $params['parent']->attrs['data-id'] ?? null;

        if ($id && app()->config->get('app.isCustomizer')) {
            $resolver->withLogger(Logger::createLogger($id, 'access'));
        }

        return $resolver->resolve($node, $params);
    }
}
