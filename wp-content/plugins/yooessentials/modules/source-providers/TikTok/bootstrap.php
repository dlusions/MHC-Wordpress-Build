<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\TikTok;

return [
    'routes' => [['post', TikTokController::PRESAVE_ENDPOINT, TikTokController::class . '@presave']],

    'yooessentials-sources' => [
        'tiktok' => TikTokSource::class,
    ],
];
