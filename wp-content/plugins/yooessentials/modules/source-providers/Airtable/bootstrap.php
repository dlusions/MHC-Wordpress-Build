<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Airtable;

return [
    'routes' => [
        ['post', AirtableController::PRESAVE_ENDPOINT, AirtableController::class . '@presave'],
        ['post', AirtableController::GET_BASES_ENDPOINT, AirtableController::class . '@bases'],
        ['post', AirtableController::GET_TABLES_ENDPOINT, AirtableController::class . '@tables'],
        ['post', AirtableController::GET_VIEWS_ENDPOINT, AirtableController::class . '@views'],
    ],

    'yooessentials-sources' => [
        'airtable' => AirtableSource::class,
    ],
];
