<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Wordpress\Rule\User;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Condition\ConditionRule;

class UserRule extends ConditionRule
{
    protected \WP_User $user;

    public function __construct(array $data)
    {
        $this->user = \wp_get_current_user();

        parent::__construct($data);
    }

    public function logArgs(object $props): array
    {
        return [
            'User ID' => $this->user->ID
        ];
    }

    public function resolve($props, $node): bool
    {
        if (!isset($props->users)) {
            throw new \RuntimeException('Not Valid Input');
        }

        $users = $props->users;

        if (is_string($users)) {
            $users = explode(',', str_replace([' ', "\r", "\n"], ['', '', ','], $users));
        }

        return Arr::some($users, fn ($user) => is_numeric($user)
            ? (int) $this->user->ID === (int) $user
            : $this->user->user_login === $user);
    }

    public function fields(): array
    {
        return [
            'users' => [
                'label' => 'Selection',
                'source' => true,
                'type' => 'textarea',
                'attrs' => [
                    'rows' => 4,
                    'placeholder' => "346\nusername",
                ],
                'description' => 'The list of User ID or Usernames that the current user must match. Separate the entries with a comma and/or new line.',
            ],
        ];
    }
}
