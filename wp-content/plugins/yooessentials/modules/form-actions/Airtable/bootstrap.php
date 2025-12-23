<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Airtable;

return [
    'routes' => [
        [
            'post',
            AirtableController::GET_BASES_ENDPOINT,
            AirtableController::class . '@getBases',
        ],
        [
            'post',
            AirtableController::GET_TABLES_ENDPOINT,
            AirtableController::class . '@getTables',
        ],
        [
            'post',
            AirtableController::GET_FIELDS_ENDPOINT,
            AirtableController::class . '@getFields',
        ],
    ],

    'yooessentials-form-actions' => [
        AirtableActionUpsert::class => __DIR__ . '/record-upsert.json',
        AirtableActionDelete::class => __DIR__ . '/record-delete.json',
    ],
];
