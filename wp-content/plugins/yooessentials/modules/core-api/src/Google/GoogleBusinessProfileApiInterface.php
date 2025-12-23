<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

interface GoogleBusinessProfileApiInterface
{
    public function accounts(array $args = []): array;

    public function location(string $location, array $args = []): array;

    public function locations(string $account, array $args = []): array;

    public function review(string $review): array;

    public function reviews(string $account, string $location, array $args = []): array;

    public function media(string $account, string $location, array $args = []): array;

    public function posts(string $account, string $location, array $args = []): array;
}
