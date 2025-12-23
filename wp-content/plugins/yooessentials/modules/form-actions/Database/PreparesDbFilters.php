<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Database;

use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Database\DatabaseQuery;
use ZOOlanders\YOOessentials\Util\Arr;
use function YOOtheme\app;

trait PreparesDbFilters
{
    protected function countRecords(object $config, Database $db): int
    {
        /** @var DatabaseQuery $query */
        $query = app(DatabaseQuery::class);
        $query = $query->createForDatabase($db);
        $query = $query
            ->select([
                'COUNT(*) as ' . $db->quoteNameStr(['results']),
            ])
            ->from($config->table);

        $filters = $this->getFiltersFromConfig($config);
        $params = $this->prepareStatementParams($config);

        foreach ($filters as $key => $value) {
            $query = $query->where($db->quoteNameStr([$config->table, $key]), '=', ":{$params[$key]}");
        }

        $result = $db->fetchArray((string) $query, $this->prepareStatementParamValues($config));

        return (int) ($result['results'] ?? ($result[0] ?? 0));
    }

    /**
     * @returns ['id' => 123, 'name' => 'test']
     */
    protected function getFiltersFromConfig(object $config): array
    {
        return Arr::mapWithKeys($config->table_record ?? [], fn (array $where) => [
            $where['props']['table_key'] => $where['props']['table_key_value'],
        ]);
    }

    /**
     * @returns ['id' => 'table_value_FF88']
     */
    protected function prepareStatementParams(object $config): array
    {
        return Arr::mapWithKeys($config->table_record ?? [], fn (array $where, $index) => [
            $where['props']['table_key'] => "table_value_{$index}",
        ]);
    }

    /**
     * @returns ['table_value_FF88' => 'value']
     */
    protected function prepareStatementParamValues(object $config): array
    {
        return Arr::mapWithKeys($config->table_record ?? [], fn (array $where, $index) => [
            "table_value_{$index}" => $where['props']['table_key_value'],
        ]);
    }
}
