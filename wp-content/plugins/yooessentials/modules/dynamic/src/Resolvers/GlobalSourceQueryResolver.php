<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Resolvers;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverAbstract;

class GlobalSourceQueryResolver extends SourceResolverAbstract
{
    public const GLOBAL = '~global';

    public const GLOBAL_QUERIES_CONFIG_KEY = 'dynamic.queries';

    protected $queries;

    public function __construct(Config $config)
    {
        $this->queries = $config->get(self::GLOBAL_QUERIES_CONFIG_KEY, []);
    }

    public function preload(object $source, object $node, array $params)
    {
        if (self::isGlobal($source)) {
            $source->query = self::resolveGlobalQuery($source->query->name, $this->queries);
        }
    }

    protected static function isGlobal(object $source): bool
    {
        return Str::startsWith($source->query->name ?? '', self::GLOBAL);
    }

    protected static function resolveGlobalQuery(string $query, $queries = []): ?object
    {
        $id = str_replace(self::GLOBAL . ':', '', $query);
        $key = array_search($id, array_column($queries, 'id'));

        if (isset($queries[$key])) {
            return json_decode(json_encode($queries[$key]['source']['query'] ?? []));
        }

        return null;
    }
}
