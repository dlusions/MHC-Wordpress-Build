<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\AcyMailing;

use AcyMailing\Classes\ListClass;
use AcyMailing\Classes\UserClass;
use AcyMailing\Classes\FieldClass;

class AcyMailingApi implements AcyMailingApiInterface
{
    public function __construct()
    {
        $acyHelper = self::helperPath();

        if (!$acyHelper || !include_once($acyHelper)) {
            throw new \Exception('AcyMailing component not found.');
        }
    }

    public function upsertSubscriber(object $subscriber, array $customFields): object
    {
        $userClass = new UserClass();

        $user = $userClass->getOneByEmail($subscriber->email);

        if ($user) {
            $subscriber = (object) array_merge((array) $user, (array) $subscriber);
        }

        $userId = $userClass->save($subscriber, $customFields);

        if (!$userId) {
            throw new \Exception($userClass->errors[0] ?? 'An error occurred while creating or updating subscriber.');
        }

        return $subscriber;
    }

    public function getSubscriberByEmail(string $email): ?object
    {
        $userClass = new UserClass();

        $subscriber = $userClass->getOneByEmail($email);

        if (!$subscriber) {
            return null;
        }

        $subscriptions = $userClass->getUserSubscriptionById($subscriber->id, 'id', false, false);

        $subscriber->subscriptions = array_values($subscriptions);

        return $subscriber;
    }

    public function subscribe(int $subscriberId, array $listIds): void
    {
        $userClass = new UserClass();
        $activeLists = $this->getActiveListIds($subscriberId);

        // skip lists already subscribed to (avoids triggering an error)
        $listIds = array_filter($listIds, fn ($list) => !in_array($list, $activeLists));

        if (!$listIds) {
            return;
        }

        // the last two parameters are to make sure to send the welcome email
        $result = $userClass->subscribe($subscriberId, $listIds, true, true);

        if (!$result) {
            throw new \Exception($userClass->errors[0] ?? 'An error occurred while subscribing to a list.');
        }
    }

    public function unsubscribe(int $subscriberId, array $listIds): void
    {
        $userClass = new UserClass();
        $activeLists = $this->getActiveListIds($subscriberId);

        // skip lists already unsubscribed from (avoids triggering an error)
        $listIds = array_filter($listIds, fn ($list) => in_array($list, $activeLists));

        if (!$listIds) {
            return;
        }

        $result = $userClass->unsubscribe($subscriberId, $listIds);

        if (!$result) {
            throw new \Exception($userClass->errors[0] ?? 'An error occurred while unsubscribing from a list');
        }
    }

    public function getLists(array $options = []): array
    {
        return (new ListClass())->getXLists($options);
    }

    public function getCustomFields(): array
    {
        $fieldClass = new FieldClass();
        $fields = $fieldClass->getAllFieldsForUser();

        $fields = array_filter($fields, fn ($field) => !$field->core);

        foreach ($fields as $field) {
            $field->value = json_decode($field->value);
            $field->option = json_decode($field->option);
        }

        return $fields;
    }

    private function getActiveListIds(int $subscriberId): array
    {
        $userClass = new UserClass();

        $subscriptions = array_filter(
            $userClass->getSubscriptionStatus($subscriberId),
            fn ($subscription) => $subscription->status === 1
        );

        return array_column($subscriptions, 'list_id');
    }

    public static function helperPath(): ?string
    {
        if (defined('JPATH_ADMINISTRATOR')) {
            $path = rtrim(JPATH_ADMINISTRATOR, '/') . '/components/com_acym/helpers/helper.php';
        }

        if (defined('WP_PLUGIN_DIR')) {
            $path = WP_PLUGIN_DIR . '/acymailing/back/helpers/helper.php';
        }

        return isset($path) && file_exists($path) ? $path : null;
    }
}
