<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GooglePhotos;

return [
    'routes' => [
        [
            'post',
            GooglePhotosController::PRESAVE_ENDPOINT,
            GooglePhotosController::class . '@presave',
        ],
        [
            'post',
            GooglePhotosController::GET_ALBUMS_ENDPOINT,
            GooglePhotosController::class . '@albums',
        ],
    ],

    'yooessentials-sources' => [
        'google-photos' => GooglePhotosSource::class,
    ],
];
