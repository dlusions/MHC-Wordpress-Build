<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream;

use YOOtheme\View;

return [
    'routes' => [
        ['post', CloudflareController::GET_ACCOUNTS_ENDPOINT, CloudflareController::class . '@getAccounts'],
        ['post', CloudflareStreamController::GET_STREAM_ENDPOINT, CloudflareStreamController::class . '@getStream'],
        ['post', CloudflareStreamController::GET_STREAMS_ENDPOINT, CloudflareStreamController::class . '@getStreams'],
        ['post', CloudflareStreamController::PRE_SAVE_KEY_ENDPOINT, CloudflareStreamController::class . '@createKey'],
        ['post', CloudflareStreamController::PRE_DELETE_KEY_ENDPOINT, CloudflareStreamController::class . '@deleteKey'],
        ['post', CloudflareStreamController::PRE_SAVE_SOURCE_ENDPOINT, CloudflareStreamController::class . '@saveSource'],
    ],

    'yooessentials-sources' => [
        'cloudflare-stream' => CloudflareStreamSource::class,
    ],

    'extend' => [
        View::class => function (View $view, $app) {
            $app(ViewHelper::class)->register($view);
        }
    ],

    'services' => [
        ViewHelper::class => ViewHelper::class,
    ],
];
