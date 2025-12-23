<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky;

return [
    'routes' => [
        ['post', BlueskyController::ACTORS_ENDPOINT, BlueskyController::class . '@actors'],
        ['post', BlueskyController::PRESAVE_ENDPOINT, BlueskyController::class . '@presave'],
        ['post', BlueskyController::ACTORS_LISTS_ENDPOINT, BlueskyController::class . '@actorLists'],
    ],

    'yooessentials-sources' => [
        'bluesky' => BlueskySource::class,
    ],
];
