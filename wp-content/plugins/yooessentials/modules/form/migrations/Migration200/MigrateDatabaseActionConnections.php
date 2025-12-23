<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration200;

use function YOOtheme\app;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Config\ConfigEncrypter;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// move db connections to db-auth
class MigrateDatabaseActionConnections extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.0.0';

    public function migrateNode(object $node, array $params): void
    {
        if (!isset($node->props['yooessentials_form'], $node->formid)) {
            return;
        }

        $formConfig = (object) ($node->props['yooessentials_form'] ?? []);
        $actions = $formConfig->after_submit_actions ?? [];

        if (empty($actions)) {
            return;
        }

        /** @var AuthManager $authManager */
        $authManager = app(AuthManager::class);

        /** @var ConfigEncrypter $encrypter */
        $encrypter = app(ConfigEncrypter::class);

        $newauths = [];

        // move database custom connections to db driver
        array_walk($actions, function (object &$action) use (&$newauths, $encrypter) {
            if (($action->type ?? '') !== 'save-database') {
                return;
            }

            $actionConfig = (array) ($action->props ?? []);
            $keysToMove = ['db_user', 'db_password', 'db_port', 'db_host'];

            $auth = array_fill_keys($keysToMove, null);
            $auth = array_merge($auth, Arr::pick($actionConfig, $keysToMove));

            if (empty(array_filter($auth))) {
                return;
            }

            foreach ($keysToMove as $key) {
                unset($actionConfig[$key]);
            }

            $id = sha1(json_encode($auth) ?: uniqid());

            $auth['id'] = $id;
            $auth['driver'] = 'database';
            $auth['db_password'] = $encrypter->encrypt($auth['db_password']);

            $newauths[$id] = $auth;

            $actionConfig['db_connection'] = $id;
            $action->props = (object) $actionConfig;
        });

        $formConfig->after_submit_actions = $actions;
        $node->props['yooessentials_form'] = $formConfig;

        if (!empty($newauths)) {
            $auths = $authManager->authConfigs();
            $auths = array_merge([], $auths, array_values($newauths));

            $authManager->saveAuths($auths);
        }
    }
}
