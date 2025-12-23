<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('WPINC') or die();

require_once __DIR__ . '/UpdateChecker.php';

/**
 * Inspired by AdminTools wordpress package
 * Credits and original Copyright to Nicholas Dyonisopoulous
 * https://www.akeebabackup.com
 */
abstract class YooessentialsUpdater
{
    /**
     * Private static variable keys that belong to our frozen state, stored in a site transient.
     */
    const STATE_KEYS = [
        'needsDownloadID',
        'connectionError',
        'platformError',
        'downloadLink',
        'cantUseWpUpdate',
        'stabilityError',
    ];

    /** @var bool Do I need the Download ID? */
    protected static $needsDownloadID = false;

    /** @var bool Did I have a connection error while */
    protected static $connectionError = false;

    /** @var bool Do I have a platform error? (Wrong PHP or WP version) */
    protected static $platformError = false;

    /** @var string Stores the download link. In this way we can run our logic only on our download links */
    protected static $downloadLink;

    /** @var bool Am I in an ancient version of WordPress, were the integrated system is not usable? */
    protected static $cantUseWpUpdate = false;

    /** @var bool Do I have an update that's less stable than my preferred stability? */
    protected static $stabilityError = false;

    /**
     * Retrieve the update information from YOOessentials for WordPress' update cache and report them back to WordPress
     * in a format it understands.
     *
     * The returned information is cached by WordPress and used by checkinfo() to render the YOOessentials for WordPress
     * update information in WordPress' Plugins page.
     *
     * @param   stdClass  $transient
     *
     * @return  stdClass
     */
    public static function getUpdates($transient)
    {
        global $wp_version;

        // On WordPress < 4.3 we can't use the integrated update system since the hook we're using to tweak
        // the installation is not available (upgrader_package_options).
        // Let's warn the user and tell him to use our own update system
        if (version_compare($wp_version, '4.3', 'lt')) {
            static::$cantUseWpUpdate = true;
            self::freezeState();

            return $transient;
        }

        static::$needsDownloadID = YOOESSENTIALS_LEVEL === 'premium';

        $updateInfo = false;

        try {
            $updateInfo = static::getUpdateInfo();
        } catch (YooEssentialsUpdaterConnectionError $e) {
            // mhm... an error occurred while connecting to the updates server. Let's notify the user
            static::$connectionError = true;
        } catch (YooEssentialsUpdaterPlatformError $e) {
            static::$platformError = true;
        } catch (YooEssentialsUpdaterStabilityError $e) {
            static::$stabilityError = true;
        }

        self::freezeState();

        if (!$updateInfo) {
            return $transient;
        }

        if (!isset($transient->response)) {
            $transient->response = [];
        }

        $obj = new stdClass();
        $obj->slug = 'yooessentials';
        $obj->plugin = 'yooessentials/yooessentials.php';
        $obj->new_version = $updateInfo->version;
        $obj->url = $updateInfo->infourl;
        $obj->package = $updateInfo->link;

        $transient->response['yooessentials/yooessentials.php'] = $obj;

        // Since the event we're hooking to is a global one (triggered for every plugin) we have to store a reference
        // of our download link. In this way we can apply our logic only on our stuff and don't interfere with other people
        static::$downloadLink = $updateInfo->link;

        return $transient;
    }

    /**
     * Used to render "View version x.x.x details" link from the plugins page.
     * We hook to this event to redirect the connection from the WordPress directory to our site for updates
     *
     * @param $cur_info
     * @param $action
     * @param $arg
     *
     * @return object
     */
    public static function checkInfo($cur_info, $action, $arg)
    {
        if (!isset($arg->slug)) {
            return $cur_info;
        }

        if ($arg->slug !== 'yooessentials') {
            return $cur_info;
        }

        try {
            $updateInfo = static::getUpdateInfo();
        } catch (Throwable $e) {
            $updateInfo = false;
        }

        // This should never occur, since if we get here, it means that we already have an update flagged
        if (!$updateInfo) {
            return $cur_info;
        }

        /**
         * This is the information WordPress is using to render the YOOessentials for WordPress row in its Plugins page.
         */
        $information = [
            // We leave the "name" index empty, so WordPress won't display the ugly title on top of our banner
            'name' => 'YOOessentials',
            'slug' => 'yooessentials',
            'author' => 'ZOOlanders',
            'homepage' => 'https://www.zoolanders.com',
            'last_updated' => $updateInfo->date ?? '',
            'version' => $updateInfo->version ?? '',
            'download_link' => $updateInfo->link ?? '',
            'requires' => '3.8',
            'tested' => get_bloginfo('version'),
            'sections' => [
                // 'description' => 'Something description',
                //'release_notes' => $updateInfo->releasenotes,
            ],
            'banners' => [
                'low' => false, // plugins_url() . '/yooessentials/images/wordpressupdate_banner.png',
                'high' => false,
            ],
        ];

        return (object) $information;
    }

    /**
     * @param bool $bailout
     * @param string $package
     * @param WP_Upgrader $upgrader
     *
     * @return WP_Error|false    An error if anything goes wrong or is missing, either case FALSE to keep the update process going
     */
    public static function addDownloadID($bailout, $package, $upgrader)
    {
        // Process only our download links
        if ($package != static::$downloadLink) {
            return false;
        }

        if (self::$needsDownloadID && !get_option('zoolanders_download_id')) {
            return new WP_Error(403, 'You need to set a Download ID');
        }

        // Our updater automatically sets the Download ID in the link, so there's no need to change anything inside the URL
        return false;
    }

    /**
     * Helper function to change some update options on the fly. By default WordPress will delete the entire folder
     * and abort if the folder already exists; by tweaking the options we can force WordPress to extract on top of the
     * existing folder deleting it first.
     *
     * @param array $options Options to be used while upgrading our plugin
     *
     * @return    array    Updated options
     */
    public static function packageOptions($options)
    {
        if (isset($options['hook_extra']) && isset($options['hook_extra']['plugin'])) {
            // If this is our package, let's tell WordPress to extract on top of the existing folder,
            // deleting anything, but stopping if it still finds the folder
            if (stripos($options['hook_extra']['plugin'], 'yooessentials') !== false) {
                $options['clear_destination'] = true;
                $options['abort_if_destination_exists'] = true;
            }
        }

        return $options;
    }

    /**
     * After performing an update, let's invoke YOOessentials install method. That will take care of updating the database
     * and any file "external" to the plugin folder (mu-plugin and auto-prepend file)
     *
     * @param WP_Upgrader $upgrader_object
     * @param array $options
     */
    public static function postUpdate($upgrader_object, $options)
    {
        // Only handle update plugins
        if (!($options['action'] == 'update' && $options['type'] == 'plugin')) {
            return;
        }

        foreach ($options['plugins'] as $plugin) {
            if ($plugin != 'yooessentials/yooessentials.php') {
                continue;
            }

            break;
        }
    }

    /**
     * Helper function to display some custom text AFTER the row regarding our update.
     * Usually is used to warn the user that something bad happened while trying to fetch new updates
     *
     * @param $plugin_file
     * @param $plugin_data
     * @param $status
     */
    public static function updateMessage($plugin_file, $plugin_data, $status)
    {
        self::thawState();

        $html = '';
        $warnings = [];

        if (static::$cantUseWpUpdate) {
            $warnings[] =
                '<p id="yooessentials-error-update-cantuseintegrated">Cannot use the automatic Wordpress Update</p>';
        } elseif (static::$needsDownloadID && !get_option('zoolanders_download_id')) {
            $settingsUrl = admin_url('options-general.php?page=yooessentials');
            $warnings[] =
                '<p id="yooessentials-error-update-nodownloadid">The Download ID has not been set. You can do so in the <a href="' . $settingsUrl . '">ZOOlanders Settings</a>.</p>';
        } elseif (static::$connectionError) {
            $warnings[] =
                '<p id="yooessentials-error-update-noconnection">We are having troubles connecting to the update site</p>';
        } elseif (static::$platformError) {
            $warnings[] =
                '<p id="yooessentials-error-update-platform-mismatch">There is a problem related to your wordpress version</p>';
        } elseif (static::$stabilityError) {
            /**
             * There is an update available but it's less stable than the minimum stability preference.
             *
             * For example: a Beta is available but we are asked to only report stable versions.
             *
             * We deliberately don't show a warning. The whole point of the stability preference is to stop buggering
             * the poor user during our pre-release runs (alphas, betas and occasional RC). In this case we just pretend
             * there is no update available, just like we do in the interface of our plugin.
             */
        }

        if ($warnings) {
            $warnings = implode('', $warnings);

            $html = <<<HTML
<tr class="plugin-update-tr active">
    <td colspan="4" class="plugin-update colspanchange">
        <div class="description-message notice inline notice-warning notice-alt">
            $warnings
        </div>
    </td>
</tr>
HTML;
        }

        if ($html) {
            echo $html;
        }
    }

    /**
     * Fetches the info from the remote server
     *
     * @return stdClass|bool
     */
    private static function getUpdateInfo()
    {
        static $updates;

        // If I already have some update info, simply return them
        if ($updates) {
            return $updates;
        }

        $updateModel = new YooEssentialsUpdateChecker();
        $updateInfo = $updateModel->getUpdateInformation();

        // No updates? Let's stop here
        if (!$updateInfo->hasUpdate) {
            // Did we get a connection error?
            if ($updateInfo->loadedUpdate == false) {
                throw new YooEssentialsUpdaterConnectionError();
            }

            // We might have an update that does not match the stability preference, e.g. RC with min. stability Stable.
            if ($updateInfo->minstabilityMatch == false) {
                throw new YooEssentialsUpdaterStabilityError();
            }

            // mhm... maybe we're on a old WordPress version?
            if (!$updateInfo->platformMatch) {
                throw new YooEssentialsUpdaterPlatformError();
            }

            return false;
        }

        return $updateInfo;
    }

    /**
     * Freeze the update warnings state in carbonite
     *
     * Just joking. We create an array with the update warnings flags and save it as a site transient.
     */
    private static function freezeState()
    {
        $frozenState = [];

        foreach (self::STATE_KEYS as $key) {
            if (isset(self::${$key})) {
                $frozenState[$key] = self::${$key};
            }
        }

        set_site_transient('yooessentials_pluginupdate_frozenstate', $frozenState);
    }

    /**
     * Unfreeze the update warnings state
     *
     * We read the site transient and restore the update warnings flags from it, if it's set.
     */
    private static function thawState()
    {
        $frozenState = get_site_transient('yooessentials_pluginupdate_frozenstate');

        if (empty($frozenState) || !is_array($frozenState)) {
            return;
        }

        foreach (self::STATE_KEYS as $key) {
            if (isset(self::${$key}) && isset($frozenState[$key])) {
                self::${$key} = $frozenState[$key];
            }
        }
    }
}

class YooEssentialsUpdaterConnectionError extends Exception
{
}
class YooEssentialsUpdaterStabilityError extends Exception
{
}
class YooEssentialsUpdaterPlatformError extends Exception
{
}
