<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Wordpress\Rule\User;

class UserLevelRule extends UserRule
{
    public function resolve($props, $node): bool
    {
        if (!isset($props->levels)) {
            throw new \RuntimeException('Not Valid Input');
        }

        $roles = $this->getUserRoles();

        // legacy code, prior v1.2 guest was an option and not an injected role
        if ($props->guest ?? false) {
            $selection[] = '_guest';
        }

        $selection = (array) ($props->levels ?? []);
        $strict = $props->strict ?? false;

        $missingRoles = array_diff($selection, $roles);

        return $strict ? count($missingRoles) === 0 : count($missingRoles) < count($selection);
    }

    public function logArgs(object $props): array
    {
        return [
            'User ID' => $this->user->ID,
            'User Roles' => $this->getUserRoles()
        ];
    }

    public function fields(): array
    {
        return [
            'levels' => [
                'label' => 'Selection',
                'type' => 'select',
                'source' => true,
                'description' => 'The role level that the current user must met. Use the shift or ctrl/cmd key to select multiple entries.',
                'attrs' => [
                    'multiple' => true,
                    'class' => 'uk-height-small uk-resize-vertical',
                ],
                'options' => $this->getRolesOptions(),
                'enable' => '!guest',
            ],
            'strict' => [
                'text' => 'All selected roles must be met',
                'type' => 'checkbox',
                'enable' => '!guest',
            ]
        ];
    }

    protected function getUserRoles(): array
    {
        $roles = $this->user->roles;

        if (!$this->user->exists()) {
            $roles[] = '_guest';
        }

        return $roles;
    }

    protected function getRolesOptions(): array
    {
        static $list = [];

        if (empty($list)) {
            require_once \ABSPATH . 'wp-admin/includes/user.php';

            $roles = array_reverse(\get_editable_roles());

            $list['Guest'] = '_guest';

            foreach ($roles as $id => $role) {
                $list[$role['name']] = $id;
            }
        }

        return $list;
    }
}
