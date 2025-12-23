<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Orders;

use ZOOlanders\YOOessentials\HasLocalConfig;

abstract class Order
{
    use HasLocalConfig;

    public const DEFAULT_DIRECTION = 'ASC';
    public const DIRECTIONS = [
        'ASC' => 'ASC',
        'DESC' => 'DESC',
    ];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function name(): string
    {
        return $this->config('name', '');
    }

    public function enabled(): bool
    {
        return !$this->disabled();
    }

    public function disabled(): bool
    {
        return $this->config('status', '') === 'disabled';
    }

    public function field(): string
    {
        return $this->config('field', '');
    }

    public function direction(): string
    {
        $direction = $this->config('direction', self::DEFAULT_DIRECTION);
        if (!in_array($direction, array_values(self::DIRECTIONS))) {
            $direction = self::DEFAULT_DIRECTION;
        }

        return $direction;
    }

    public function validate(): void
    {
        if (strlen($this->field()) <= 0) {
            throw InvalidOrderException::create('Field is required', $this->config());
        }
    }
}
