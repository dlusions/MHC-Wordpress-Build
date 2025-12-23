<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

use ZOOlanders\YOOessentials\Config\ConfigRepositoryInterface;
use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Database\DatabaseManager;
use ZOOlanders\YOOessentials\Database\DatabaseQuery;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;

return [
    'config' => function () {
        global $wpdb;
        $dbname = defined('DB_NAME') ? DB_NAME : '';
        $host = explode(':', defined('DB_HOST') ? DB_HOST : '127.0.0.1');

        return [
            'yooessentials' => [
                'timezone' => \wp_timezone_string() ?? 'UTC',
                'db' => [
                    'database' => $dbname,
                    'prefix' => $wpdb->get_blog_prefix(),
                    'host' => array_shift($host),
                    'port' => array_shift($host) ?: 3306,
                ],
                'encryption' => [
                    'key' => strtr(WP_CONTENT_DIR, '\\', '/') . '/yooessentials/secret.php'
                ]
            ],
        ];
    },

    'actions' => [
        'plugins_loaded' => [
            Wordpress\Platform::class => ['initSession', -10],
        ],

        'wp_loaded' => [
            Wordpress\Platform::class => ['handleRoute', -10],
        ],

        'wp_print_footer_scripts' => [
            Wordpress\Listener\PrintLogger::class => ['@handle', -999],
            Wordpress\ConsoleLogger::class => [['print', -999], ['alert', -999]],
        ],
    ],

    'events' => [
        'customizer.init' => [
            Wordpress\Listener\LoadGeoipDb::class => ['@handle', 10],
            Wordpress\Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'app.request' => [
            Wordpress\Listener\CsrfTokenMiddleware::class => ['@handle', 20],
        ],
    ],

    'extend' => [
        FormSubmissionRequest::class => function (FormSubmissionRequest $submission) {
            $submission->csrfFormToken = Wordpress\Platform::printCsrfFormToken();
        },
    ],

    'services' => [
        Session::class => Wordpress\Session::class,
        Translator::class => Wordpress\Translator::class,
        Unzipper::class => Wordpress\Unzipper::class,
        Mailer::class => [
            'class' => Wordpress\Mailer::class,
            'shared' => false,
        ],
        Database::class => function () {
            global $wpdb;

            return new Wordpress\Database($wpdb);
        },
        DatabaseManager::class => Wordpress\DatabaseManager::class,
        DatabaseQuery::class => [
            'class' => Wordpress\DatabaseQuery::class,
            'shared' => false,
        ],
        ConfigRepositoryInterface::class => Wordpress\ConfigRepository::class,
        UrlInterface::class => Wordpress\Url::class,
        HttpClientInterface::class => Wordpress\HttpClient::class,
    ],
];
