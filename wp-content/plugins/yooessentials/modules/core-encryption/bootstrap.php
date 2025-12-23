<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption;

use YOOtheme\Config;
use ZOOlanders\YOOessentials\Encryption\Encrypter\FileKeyEncrypter;
use ZOOlanders\YOOessentials\Encryption\Encrypter\GenericEncrypter;
use ZOOlanders\YOOessentials\Encryption\Encrypter\SessionEncrypter;
use ZOOlanders\YOOessentials\Encryption\Encrypter\SiteKeyEncrypter;
use function YOOtheme\app;

include_once __DIR__ . '/class_aliases.php';

return [
    'services' => [
        Encrypter::class => fn () => app(SessionEncrypter::class),
        // encrypt using the session token + salt (used for user / session - unique encryption)
        SessionEncrypter::class => fn (Config $config) => new GenericEncrypter($config('session.token', ''), $config('app.secret', '')),
        // encrypt using site site secret key (deprecated)
        SiteKeyEncrypter::class => fn (Config $config) => new GenericEncrypter($config->get('app.secret', ''), ''),
        // new encryption using a dedicated file
        FileKeyEncrypter::class => '',
    ]
];
