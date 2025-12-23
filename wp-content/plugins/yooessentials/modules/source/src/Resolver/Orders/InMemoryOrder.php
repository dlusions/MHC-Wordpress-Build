<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Orders;

use ZOOlanders\YOOessentials\Source\Resolver\ExtractsRecordValue;

class InMemoryOrder extends Order
{
    use ExtractsRecordValue;

    public function apply(array $recordA, array $recordB): int
    {
        $valueA = self::extractRecordValue($this->field(), $recordA);
        $valueB = self::extractRecordValue($this->field(), $recordB);

        if ($valueA === $valueB) {
            return 0;
        }

        if ($this->direction() === self::DIRECTIONS['DESC']) {
            return $valueA > $valueB ? -1 : 1;
        }

        return $valueA > $valueB ? 1 : -1;
    }
}
