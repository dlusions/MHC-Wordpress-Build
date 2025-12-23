<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration240;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateDatabaseActions extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.4.0';

    public function migrateNode(object $node, array $params): void
    {
        if (!isset($node->props['yooessentials_form'])) {
            return;
        }

        $config = $node->props['yooessentials_form'] ?? [];

        foreach ($config->after_submit_actions ?? [] as $action) {
            if ($action->type !== 'database-record-upsert' && $action->type !== 'database-record-delete') {
                continue;
            }

            $action->props = (array) ($action->props ?? []);

            $entry = array_filter([
                'id' => '5551A9', // is only one per action, doesnt have to be unique
                'props' => Arr::pick($action->props, [
                    'table_key',
                    'table_key_value',
                ]),

                // we can copy entire source_extended object
                // as no other prop is dynamic on this level
                'source_extended' => $action->source_extended ?? null
            ]);

            if (empty(Arr::omit($entry, ['id']))) {
                continue;
            }

            $action->props['table_record'] = [$entry];

            // cleanup
            unset($action->props['table_key']);
            unset($action->props['table_key_value']);
            unset($action->source_extended);
        }

        $node->props['yooessentials_form'] = $config;
    }
}
