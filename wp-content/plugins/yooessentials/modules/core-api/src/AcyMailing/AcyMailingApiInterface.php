<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\AcyMailing;

interface AcyMailingApiInterface
{
    public function upsertSubscriber(object $subscriber, array $customFields): object;

    public function getSubscriberByEmail(string $email): ?object;

    public function subscribe(int $subscriberId, array $listIds): void;

    public function unsubscribe(int $subscriberId, array $listIds): void;

    public function getLists(): array;

    public function getCustomFields(): array;
}
