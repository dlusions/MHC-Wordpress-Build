<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\GoogleSheets;

return [
    'routes' => [
        [
            'post',
            GoogleSheetsActionController::GET_SHEET_COLUMNS_ENDPOINT,
            GoogleSheetsActionController::class . '@getSheetColumns',
        ],
        [
            'post',
            GoogleSheetsActionController::GET_SPREADSHEET_LIST_ENDPOINT,
            GoogleSheetsActionController::class . '@getSpreadsheetList',
        ],
        [
            'post',
            GoogleSheetsActionController::GET_SPREADSHEET_SHEETS_ENDPOINT,
            GoogleSheetsActionController::class . '@getSpreadsheetSheetsList',
        ],
    ],

    'yooessentials-form-actions' => [
        GoogleSheetsUpsertAction::class => __DIR__ . '/record-upsert.json',
        GoogleSheetsDeleteAction::class => __DIR__ . '/record-delete.json',
    ],
];
