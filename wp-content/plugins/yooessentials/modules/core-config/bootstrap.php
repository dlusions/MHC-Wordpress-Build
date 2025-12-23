<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Config;

use function YOOtheme\app;
use YOOtheme\Http\Request;
use ZOOlanders\YOOessentials\Migration;
use ZOOlanders\YOOessentials\Encryption\Encrypter\FileKeyEncrypter;
use ZOOlanders\YOOessentials\Encryption\Encrypter\MultipleEncrypter;
use ZOOlanders\YOOessentials\Encryption\Encrypter\SiteKeyEncrypter;

return [
    'routes' => [
        ['post', ConfigController::CONFIG_SAVE_URL, ConfigController::class . '@save'],
        ['post', ConfigController::CONFIG_IMPORT_URL, ConfigController::class . '@import'],
        ['post', ConfigController::CONFIG_EXPORT_URL, ConfigController::class . '@export'],
    ],

    'events' => [
        'app.request' => [
            ConfigListener::class => ['loadConfigFromRequest', -10],
        ],

        'customizer.init' => [
            ConfigListener::class => ['initCustomizer', 10],
        ],

        'config.save' => [
            ConfigListener::class => 'cleanYooConfig',
        ],
    ],

    'extend' => [
        Request::class => function (Request $request, $app) {
            $requestConfig = ConfigListener::fromRequest($request);

            /** @var Config $config */
            $config = $app(Config::class);

            if ($requestConfig !== null) {
                $config->add($requestConfig);
            }
        },
    ],

    'services' => [
        Config::class => '',
        ConfigEncrypter::class => fn () => new MultipleEncrypter([
            app(FileKeyEncrypter::class),
            app(SiteKeyEncrypter::class),
        ]),
    ],

    'yooessentials-migrations' => [
        Migration\Migration160\ExtractConfigFromYooConfig::class,
    ],
];
