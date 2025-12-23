<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action;

abstract class SaveToAction extends StandardAction
{
    protected const DISABLED_STATUS = 'disabled';
    protected const DISABLED_VALUE = null;

    protected function resolveData(array $content): array
    {
        $data = [];

        foreach ($content as $row) {
            $row = (array) $row;
            $col = $row['id'] ?? '';
            $value = $row['props']['value'] ?? '';
            $status = $row['props']['status'] ?? '';

            if ($status === self::DISABLED_STATUS) {
                $value = static::DISABLED_VALUE;
            }

            $data[$col] = $value;
        }

        $mapped = array_filter($data, fn ($value) => $value !== static::DISABLED_VALUE);

        if (count($mapped) === 0) {
            throw ActionConfigurationException::create($this, 'No fields have been mapped or those have resolved to an empty value.');
        }

        foreach ($data as $col => $val) {
            if (is_array($val)) {
                throw ActionConfigurationException::create(
                    $this,
                    sprintf("Invalid mapping for column '%s', multi values are not supported.", $col),
                );
            }
        }

        return $data;
    }

    protected static function sortDataFromHeaders(array $headers, array $data): array
    {
        $orderedData = [];
        $keysOrder = array_values($headers);

        foreach ($data as $key => $value) {
            $index = array_search($key, $keysOrder);
            $orderedData[$index ?? $key] = $value;
        }

        ksort($orderedData);

        return $orderedData;
    }
}
