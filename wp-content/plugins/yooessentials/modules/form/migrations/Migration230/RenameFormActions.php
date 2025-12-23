<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RenameFormActions extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.0';

    public function migrateNode(object $node, array $params): void
    {
        if (!isset($node->props['yooessentials_form'])) {
            return;
        }

        $config = $node->props['yooessentials_form'] ?? [];

        foreach ($config->after_submit_actions ?? [] as $action) {
            // csv
            if ($action->type === 'save-csv') {
                $action->type = 'csv-record-upsert';
            }

            // database
            if ($action->type === 'save-database') {
                $action->type = 'database-record-upsert';
            }

            // google-sheets
            if ($action->type === 'save-google-sheet') {
                $action->type = 'google-sheets-record-upsert';
            }

            // mailchimp
            if ($action->type === 'mailchimp-member-add') {
                $action->type = 'mailchimp-member-upsert';
            }
        }

        $node->props['yooessentials_form'] = $config;
    }
}
