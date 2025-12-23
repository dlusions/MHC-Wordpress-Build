<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('WPINC') or die();

abstract class Yooessentials23Helper
{
    const MIN_WP_VERSION = '5.0';
    const MIN_PHP_VERSION = '7.4';
    const MIN_YTP_VERSION = '4.5.0';
    const MAX_YTP_VERSION = '4.5.99';
    const BLOCK_YTP_UPDATE = 'YES';

    public static function adminNotice(string $message): void
    {
        add_action('admin_notices', function () use ($message) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?= $message ?></p>
            </div> <?php
        });
    }

    public static function validatePlatform()
    {
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, 'lt')) {
            throw new RuntimeException(
                sprintf(
                    'Essentials plugin requires a more recent version of PHP, please update PHP to v%s or later.',
                    self::MIN_PHP_VERSION
                )
            );
        }

        if (version_compare($GLOBALS['wp_version'], self::MIN_WP_VERSION, 'lt')) {
            throw new RuntimeException(
                sprintf(
                    'Essentials plugin requires a more recent version of WordPress, please update WordPress to v%s or later.',
                    self::MIN_WP_VERSION
                )
            );
        }

        $theme = self::getYootheme();

        if ($theme === null || version_compare($theme->get('Version'), self::MIN_YTP_VERSION, 'lt')) {
            throw new RuntimeException(
                sprintf(
                    'Essentials plugin requires a more recent version of YOOtheme Pro, please update YOOtheme Pro to v%s or later.',
                    self::MIN_YTP_VERSION
                )
            );
        }

        if (version_compare($theme->get('Version'), self::MAX_YTP_VERSION, 'gt')) {
            throw new RuntimeException(
                sprintf(
                    'The currently installed Essentials plugin does not support YOOtheme Pro v%s, try updating Essentials to its latest version or contact support for further help.',
                    $theme->get('Version')
                )
            );
        }
    }

    // checks if the theme being installed is compatible with the plugin
    public static function preinstallThemeCheck($source, $remote_source)
    {
        if (is_wp_error($source)) {
            return $source;
        }

        $theme = wp_get_theme('yootheme', $remote_source);

        if ($theme->get('Name') !== 'YOOtheme') {
            return $source;
        }

        $version = $theme->get('Version');

        if (
            version_compare($version, self::MIN_YTP_VERSION, 'lt') ||
            (self::BLOCK_YTP_UPDATE === 'YES' && version_compare($version, self::MAX_YTP_VERSION, 'gt'))
        ) {
            return new WP_Error(
                400,
                sprintf(
                    'YOOtheme Pro v%s is not supported by the currently installed Essentials plugin, disable Essentials before retrying.',
                    $version
                )
            );
        }

        return $source;
    }

    public static function validateChecksums(bool $force = false): void
    {
        $root = dirname(__DIR__);
        $checksums = "$root/checksums";
        $checksumsPass = "$root/checksums.pass";

        if (!$force && file_exists($checksumsPass)) {
            return;
        }

        $file = file_exists($checksums) ? fopen($checksums, 'r') : null;

        // skip if checksum file is missing
        if (!$file) {
            return;
        }

        $log = [];
        while ($row = fgets($file)) {
            list($md5, $fileName) = explode(' ', trim($row), 2);

            $filePath = $root . '/' . trim($fileName);

            if (!file_exists($filePath)) {
                $log['missing'][] = $filePath;

                continue;
            }

            $fileMd5 = md5_file($filePath);

            if ($fileMd5 !== $md5) {
                $log['changed'][] = $filePath;
            }
        }

        if (empty($log)) {
            ($file = fopen($checksumsPass, 'w')) or die('Unable to create file!');
            fwrite($file, 'OK');
            fclose($file);
        }

        if (!empty($log)) {
            $message = 'Essentials plugin execution has been prevented due to corrupted installation or altered files. Please reinstall Essentials.';
            $message .= '<br>';

            foreach ($log as $type => $files) {
                $type = strtoupper($type);
                foreach ($files as $file) {
                    $message .= "<br>$type - $file";
                }
            }

            throw new RuntimeException($message);
        }
    }

    private static function getYootheme(): ?WP_Theme
    {
        $theme = wp_get_theme();

        if ($parent = $theme->parent()) {
            $theme = $parent;
        }

        if ($theme->exists() && stripos($theme->get_template(), 'yootheme') === 0) {
            return $theme;
        }

        return null;
    }

    public static function fetchConfig(): array
    {
        return json_decode(get_option('yooessentials'), true) ?: [];
    }

    public static function clearCache($files = null)
    {
        $files = $files ?: self::getCacheFiles();

        foreach ($files as $file) {
            if (is_iterable($file)) {
                self::clearCache($file);
            } elseif ($file->isFile()) {
                unlink($file->getRealPath());
            } elseif ($file->isDir()) {
                rmdir($file->getRealPath());
            }
        }
    }

    private static function getCacheFiles()
    {
        $theme = self::getYootheme();

        $files = [new SplFileInfo($theme->get_template_directory() . '/cache/schema-1.gql')];

        $cachePath = $theme->get_template_directory() . '/cache/yooessentials';

        if (file_exists($cachePath)) {
            $iterator = new RecursiveDirectoryIterator($cachePath, FilesystemIterator::SKIP_DOTS);
            $files[] = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
        }

        return $files;
    }
}
