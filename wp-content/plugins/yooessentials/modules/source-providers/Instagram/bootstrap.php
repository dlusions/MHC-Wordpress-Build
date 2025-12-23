<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram;

return [
    'routes' => [
        ['post', InstagramController::PRESAVE_ENDPOINT, InstagramController::class . '@presave'],
        ['post', InstagramController::PAGES_ENDPOINT, InstagramController::class . '@pages', ['allowed' => true]],
    ],

    'yooessentials-sources' => [
        'instagram' => InstagramSource::class,
    ],
];
