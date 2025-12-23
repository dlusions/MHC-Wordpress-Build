<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use function YOOtheme\app;
use YOOtheme\Path;
use YOOtheme\Application;

defined('WPINC') or die();

add_action('after_setup_theme', function () {
    if (!class_exists(Application::class, false)) {
        return;
    }

    include_once __DIR__ . '/modules/autoload.php';

    Path::setAlias('~yooessentials', __DIR__);
    Path::setAlias('~yooessentials_url', plugins_url('yooessentials'));

    $config = Yooessentials23Helper::fetchConfig();

    $modules = [
        'platform',
        'core{,-{encryption,migration,api,auth{,-drivers},condition{,-rules},config,debug,storage{,-adapters}}}',
    ];

    $addons = [
        'access' => '',
        'layout' => '',
        'dynamic' => '',
        'element' => '',
        'icon' => 'icon{,-collections}',
        'form' => 'form{,-actions,-elements}',
        'source' => 'source{,-providers,-sources}',
    ];

    foreach ($addons as $addon => $module) {
        if ($config[$addon]['state'] ?? true) {
            $modules[] = $module ?: $addon;
        }
    }

    app()->load('~yooessentials/modules/{' . implode(',', $modules) . '}{,-wordpress}/bootstrap.php');
});
