<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleSheets;

return [
    'routes' => [
        ['post', GoogleSheetsController::PRESAVE_ENDPOINT, GoogleSheetsController::class . '@presave'],
        ['post', GoogleSheetsController::GET_SHEETS_ENDPOINT, GoogleSheetsController::class . '@sheets'],
        ['post', GoogleSheetsController::GET_SPREADSHEETS_ENDPOINT, GoogleSheetsController::class . '@spreadsheets'],
    ],

    'yooessentials-sources' => [
        'google-sheets' => GoogleSheetsSource::class,
    ],
];
