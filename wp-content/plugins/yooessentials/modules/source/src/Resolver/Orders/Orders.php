<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Orders;

use YOOtheme\Event;
use ZOOlanders\YOOessentials\Util\Arr;

abstract class Orders
{
    /**
     * @var Order[]
     */
    protected array $orders;

    public function __construct(array $orders)
    {
        $this->orders = Arr::map(Arr::filter($orders), fn (array $order) => $this->createOrder($order));
    }

    public function enabled(): array
    {
        return Arr::filter($this->orders, function (Order $order) {
            try {
                $order->validate();
            } catch (InvalidOrderException $e) {
                Event::emit('yooessentials.info', [
                    'group' => 'YOOessentials Invalid Order - Disabled',
                    'addon' => 'source',
                    'error' => $e->getMessage(),
                ]);

                return false;
            }

            return $order->enabled();
        });
    }

    /**
     * @param array $order
     * @return Order
     */
    protected function createOrder(array $order)
    {
        return new InMemoryOrder($order);
    }
}
