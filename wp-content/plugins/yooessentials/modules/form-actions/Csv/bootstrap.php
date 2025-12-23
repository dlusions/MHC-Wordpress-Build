<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Csv;

return [
    'routes' => [
        ['post', CsvActionController::GET_COLUMNS_ENDPOINT, CsvActionController::class . '@getColumns'],
    ],

    'yooessentials-form-actions' => [
        CsvUpsertAction::class => __DIR__ . '/record-upsert.json',
        CsvDeleteAction::class => __DIR__ . '/record-delete.json',
    ],
];
