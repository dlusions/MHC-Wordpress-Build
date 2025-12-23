<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Wordpress\Rule\UserGroup;

use ZOOlanders\YOOessentials\Condition\ConditionRule;

class UserGroupRule extends ConditionRule
{
    protected \WP_User $user;

    public function __construct(array $data)
    {
        $this->user = \wp_get_current_user();

        parent::__construct($data);
    }

    public function resolve($props, $node): bool
    {
        if (!isset($props->levels)) {
            throw new \RuntimeException('Not Valid Input');
        }

        if (!$this->user->exists()) {
            return false;
        }

        require_once \GROUPS_CORE_LIB . '/class-groups-user.php';

        $selection = (array) ($props->levels ?? []);
        $strict = $props->strict ?? false;

        $roles = [];
        foreach ($selection as $group) {
            if (\Groups_User::user_is_member($this->user, $group)) {
                $roles[] = $group;
            }
        }

        $missingRoles = array_diff($selection, $roles);
        $hasAllRoles = count($missingRoles) === 0;

        return $strict ? $hasAllRoles : count($roles) > 0;
    }

    public function logArgs(object $props): array
    {
        return [
            'User ID' => $this->user->ID,
        ];
    }

    public function fields(): array
    {
        if (!defined('GROUPS_CORE_LIB')) {
            return [
                '_info' => [
                    'type' => 'yooessentials-info',
                    'content' => 'This rule requires the <a href="https://wordpress.org/plugins/groups" target="_blank">Groups Plugin</a> to be installed and enabled.'
                ],
            ];
        }

        return [
            'levels' => [
                'label' => 'Selection',
                'type' => 'select',
                'source' => true,
                'description' => 'The group that the current user must met. Use the <code>shift</code> or <code>ctrl/cmd</code> key to select multiple entries.',
                'attrs' => [
                    'multiple' => true,
                    'class' => 'uk-height-small uk-resize-vertical',
                ],
                'options' => $this->getUserGroupsOptions(),
                'enable' => '!guest',
            ],
            'strict' => [
                'text' => 'All selected groups must be met',
                'type' => 'checkbox',
                'enable' => '!guest',
            ]
        ];
    }

    protected function getUserGroupsOptions(): array
    {
        static $list = [];

        if (empty($list)) {
            $list = self::get_group_tree();
        }

        return $list;
    }

    protected static function get_group_tree(array &$tree = [], ?int $parent_id = null, int $depth = 0): array
    {
        if (!defined('GROUPS_CORE_LIB')) {
            return [];
        }

        require_once \GROUPS_CORE_LIB . '/wp-init.php';
        require_once \GROUPS_CORE_LIB . '/class-groups-utility.php';

        global $wpdb;
        $group_table = \_groups_get_tablename('group');

        $groups = $wpdb->get_results(
            is_null($parent_id) ?
                "SELECT group_id, name FROM $group_table WHERE parent_id IS NULL ORDER BY group_id" :
                $wpdb->prepare("SELECT group_id, name FROM $group_table WHERE parent_id = %d ORDER BY group_id", $parent_id)
        );

        if ($groups) {
            foreach ($groups as $group) {
                $group_id = \Groups_Utility::id($group->group_id);
                $formatted_name = str_repeat('-', $depth) . ' ' . $group->name;
                $tree[$formatted_name] = $group_id;

                self::get_group_tree($tree, $group_id, $depth + 1);
            }
        }

        return $tree;
    }
}
