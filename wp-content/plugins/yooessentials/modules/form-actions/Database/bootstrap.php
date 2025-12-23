<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Database;

return [
    'routes' => [
        ['post', 'yooessentials/form/action/savedb/tables', DatabaseActionController::class . '@getTableList'],
        ['post', 'yooessentials/form/action/savedb/columns', DatabaseActionController::class . '@getTableColumns'],
        ['post', 'yooessentials/form/action/savedb/fields', DatabaseActionController::class . '@getTableFields'],
    ],

    'yooessentials-form-actions' => [
        DatabaseUpsertAction::class => __DIR__ . '/record-upsert.json',
        DatabaseDeleteAction::class => __DIR__ . '/record-delete.json'
    ],
];
