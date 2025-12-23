<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
 * Plugin Name: Essentials YOOtheme Pro
 * Plugin URI: http://www.zoolanders.com/essentials-for-yootheme-pro
 * Description: Essential Addons for YOOtheme Pro by ZOOlanders
 * Author: ZOOlanders
 * Author URI: https://www.zoolanders.com
 * Version: 2.4.12
 */

defined('WPINC') or die();

define('YOOESSENTIALS_VERSION', '2.4.12');
define('YOOESSENTIALS_LEVEL', 'premium');

if (!class_exists('Yooessentials23Helper')) {
    require_once __DIR__ . '/classes/Helper.php';
}

if (!class_exists('YooessentialsUpdater')) {
    require_once __DIR__ . '/classes/Updater.php';
}

if (!class_exists('YooessentialsSettings')) {
    require_once __DIR__ . '/classes/Settings.php';
}

// installer hooks
add_filter('upgrader_source_selection', [Yooessentials23Helper::class, 'preinstallThemeCheck'], 10, 2);

// updater hooks
add_filter('pre_set_site_transient_update_plugins', [YooessentialsUpdater::class, 'getUpdates'], 10, 2);
add_filter('plugins_api', [YooessentialsUpdater::class, 'checkInfo'], 10, 3);
add_filter('upgrader_pre_download', [YooessentialsUpdater::class, 'addDownloadID'], 10, 3);
add_filter('upgrader_package_options', [YooessentialsUpdater::class, 'packageOptions'], 10, 2);
add_action('upgrader_process_complete', [YooessentialsUpdater::class, 'postUpdate'], 10, 2);
add_filter('after_plugin_row_yooessentials/yooessentials.php', [YooessentialsUpdater::class, 'updateMessage'], 10, 3);

// settings page hooks
add_action('admin_init', [YooessentialsSettings::class, 'settings']);
add_action('admin_menu', [YooessentialsSettings::class, 'settingsMenu']);
add_filter('plugin_action_links_yooessentials/yooessentials.php', [YooessentialsSettings::class, 'settingsLink']);

try {
    Yooessentials23Helper::validatePlatform();
    Yooessentials23Helper::validateChecksums();
} catch (RuntimeException $e) {
    Yooessentials23Helper::adminNotice($e->getMessage());

    return;
}

register_activation_hook(__FILE__, function () {
    try {
        Yooessentials23Helper::validateChecksums(true);
    } catch (RuntimeException $e) {
        Yooessentials23Helper::adminNotice($e->getMessage());

        return;
    }

    Yooessentials23Helper::clearCache();
});

require_once __DIR__ . '/load-modules.php';
