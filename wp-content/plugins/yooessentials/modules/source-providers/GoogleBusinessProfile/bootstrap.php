<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile;

return [
    'routes' => [
        ['post', GoogleBusinessProfileController::PRESAVE_ENDPOINT, GoogleBusinessProfileController::class . '@presave'],
        [
            'post',
            GoogleBusinessProfileController::GET_ACCOUNTS_ENDPOINT,
            GoogleBusinessProfileController::class . '@accounts',
        ],
        [
            'post',
            GoogleBusinessProfileController::GET_LOCATIONS_ENDPOINT,
            GoogleBusinessProfileController::class . '@locations',
        ],
        ['post', GoogleBusinessProfileController::GET_REVIEWS_ENDPOINT, GoogleBusinessProfileController::class . '@reviews'],
    ],

    'yooessentials-sources' => [
        'google-business-profile' => GoogleBusinessProfileSource::class,
    ],
];
