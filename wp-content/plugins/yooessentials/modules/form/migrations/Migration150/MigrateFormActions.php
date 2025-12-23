<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration150;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Form\Action\Action;
use ZOOlanders\YOOessentials\Form\Action\ActionManager;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateFormActions extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.5.0';

    public function migrateNode(object $node, array $params): void
    {
        if (!isset($node->props['yooessentials_form'], $node->formid)) {
            return;
        }

        /** @var ActionManager $manager */
        $manager = app(ActionManager::class);

        $config = $node->props['yooessentials_form'] ?? [];

        // Move action keys from class name to action name
        foreach ($config->after_submit_actions ?? [] as $action) {
            // Old Action Key, and we still know the class
            if (class_exists($action->type ?? '')) {
                $actionClass = app($action->type);
                if ($actionClass instanceof Action) {
                    $actionClass = $manager->actionFromClassName(get_class($actionClass));
                    $action->type = $actionClass->name() ?? '';
                }
            }
        }

        // Generate Ids for Form actions
        foreach ($config->after_submit_actions ?? [] as $action) {
            $action->id ??= uniqid();
        }

        $node->props['yooessentials_form'] = $config;
    }
}
