<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('WPINC') or die();

/**
 * Inspired by AdminTools wordpress package
 * Credits and original Copyright to Nicholas Dyonisopoulous
 * https://www.akeebabackup.com
 */
class YooessentialsUpdateChecker
{
    /** @var string The URL containing the INI update stream URL */
    protected string $updateStreamURL;

    /** @var stdClass A registry object holding the update information */
    protected $updateInfo = null;

    /** @var string Currently installed version */
    protected string $currentVersion;

    protected $downloadId = '';

    /**
     * How to determine if a new version is available. 'different' = if the version number is different,
     * the remote version is newer, 'vcompare' = use version compare between the two versions, 'newest' =
     * compare the release dates to find the newest. I suggest using 'different' on most cases.
     */
    protected string $versionStrategy;
    /**
     * @var string
     */
    protected $minStability;

    public function __construct()
    {
        $this->currentVersion = defined('YOOESSENTIALS_VERSION') ? YOOESSENTIALS_VERSION : '0.0.0';
        $this->downloadId = get_option('zoolanders_download_id');
        $this->minStability = get_option('yooessentials_min_stability', 'stable');

        /**
         * If the current version is an Alpha, Beta or RC override the minimum stability to the same stability level as
         * the version currently installed. This lets people testing unstable versions to update to the next unstable
         * version instead of waiting for us to release a stable. This is especially useful during beta testing phases
         * of new releases.
         */
        $currentStability = $this->getStability($this->sanitiseVersion($this->currentVersion));

        if ($currentStability != 'stable') {
            $this->minStability = $currentStability;
        }

        $level = YOOESSENTIALS_LEVEL === 'premium' ? 'premium' : 'free';
        $this->updateStreamURL = 'http://static.zoolanders.com/updates/yooessentials-wp-' . $level . '.xml';

        $this->versionStrategy = 'smart';

        $this->load();
    }

    /**
     * Load the update information into the $this->updateInfo object. The update information will be returned from the
     * cache. If the cache is expired, the $force flag is set or the ADMINTOOLSWP_PATH  . 'update.ini' file is present the
     * update information will be reloaded from the source. The update source normally is $this->updateStreamURL. If
     * the APATH_BASE  . 'update.ini' file is present it's used as the update source instead.
     *
     * In short, the ADMINTOOLSWP_PATH  . 'update.ini' file allows you to override update sources for testing purposes.
     *
     * @return  void
     */
    public function load()
    {
        // Clear the update information and last update check timestamp
        $this->updateInfo = null;

        $this->updateInfo = new stdClass();
        $this->updateInfo->stuck = false;
        $this->updateInfo->loadedUpdate = false;
        $this->updateInfo->hasUpdate = false;

        $download = (new WP_Http())->get($this->updateStreamURL);

        if (is_wp_error($download) or !($download['body'] ?? false)) {
            return;
        }

        try {
            $updateInfo = simplexml_load_string($download['body']);
        } catch (Throwable $e) {
            return;
        }

        if ($updateInfo) {
            $this->updateInfo->loadedUpdate = true;
            $this->updateInfo->stuck = false;
        }

        foreach ($updateInfo->update as $update) {
            if (!$this->hasUpdate($update)) {
                continue;
            }

            // If not stuck, loadedUpdate is 1, version key exists and stability key does not exist / is empty, determine the version stability
            $version = $this->sanitiseVersion((string) $update->version);
            $stability = (string) $update->stability;

            // Skip if we already have a newer version
            if (
                ($this->updateInfo->version ?? false) &&
                version_compare($this->sanitiseVersion($this->updateInfo->version), $version, 'gt')
            ) {
                continue;
            }

            if (!$this->updateInfo->stuck && $this->updateInfo->loadedUpdate && !empty($version) && empty($stability)) {
                $this->updateInfo->stability = $this->getStability($version);
            }

            // Check if an update is available and push it to the update information registry
            $this->updateInfo->hasUpdate = true;

            // Post-process the download URL, appending the Download ID (if defined)
            $link = (string) $update->downloads[0]->downloadurl;

            if (!empty($link) && !empty($this->downloadId)) {
                $queryString = parse_url($link, PHP_URL_QUERY) ?? '';
                parse_str($queryString, $parameters);
                $parameters = array_merge($parameters, [
                    'dlid' => $this->downloadId,
                ]);
                $query = http_build_query($parameters, '', '&');
                if (($offset = stripos($link, '?')) !== false) {
                    $link = substr($link, 0, $offset);
                }

                $link .= '?' . $query;
            }

            $this->updateInfo->version = $version;
            $this->updateInfo->link = trim($link);
            $this->updateInfo->infourl = (string) $update->infourl;
        }
    }

    /**
     * Is there an update available?
     *
     * @param $update SimpleXmlElement
     * @return  bool
     */
    public function hasUpdate($update)
    {
        global $wp_version;

        $this->updateInfo->minstabilityMatch = 1;
        $this->updateInfo->platformMatch = 1;

        if (!isset($this->updateInfo->stability)) {
            $this->updateInfo->stability = 'stable';
        }

        // Validate the minimum stability
        $stability = strtolower($update->stability);

        switch ($this->minStability) {
            case 'alpha':
            default:
                // Reports any stability level as an available update
                break;

            case 'beta':
                // Do not report alphas as available updates
                if (in_array($stability, ['alpha'])) {
                    $this->updateInfo->minstabilityMatch = 0;

                    return false;
                }

                break;

            case 'rc':
                // Do not report alphas and betas as available updates
                if (in_array($stability, ['alpha', 'beta'])) {
                    $this->updateInfo->minstabilityMatch = 0;

                    return false;
                }

                break;

            case 'stable':
                // Do not report alphas, betas and rcs as available updates
                if (in_array($stability, ['alpha', 'beta', 'rc'])) {
                    $this->updateInfo->minstabilityMatch = 0;

                    return false;
                }

                break;
        }

        $platform = trim((string) $update->targetplatform['name']);
        $platformName = strtolower($platform);
        $platformVersion = (string) $update->targetplatform['version'];

        $platformFound = false;

        if (substr($platformVersion, -1) == '+' && version_compare($wp_version, substr($platformVersion, 0, -1), 'ge')) {
            $this->updateInfo->platformMatch = 1;
            $platformFound = true;
        } elseif ($platformName == 'wordpress') {
            $this->updateInfo->platformMatch = 1;
            $platformFound = true;
        }

        if (!$platformFound) {
            return false;
        }

        // Apply the version strategy
        $version = (string) $update->version;

        if (empty($version)) {
            return false;
        }

        return $this->hasUpdateByVersion($version);
    }

    /**
     * Returns the update information
     *
     * @return stdClass
     */
    public function getUpdateInformation()
    {
        if (is_null($this->updateInfo)) {
            $this->load();
        }

        return $this->updateInfo;
    }

    /**
     * Finalises the update. Reserved for future use. DO NOT REMOVE.
     */
    public function finalise()
    {
        // Reserved for future use. DO NOT REMOVE.
    }

    /**
     * Get the currently used update stream URL
     *
     * @return string
     */
    public function getUpdateStreamURL()
    {
        return $this->updateStreamURL;
    }

    /**
     * Normalise the version number to a PHP-format version string.
     *
     * @param string $version The whatever-format version number
     *
     * @return  string  A standard formatted version number
     */
    public function sanitiseVersion($version)
    {
        $test = strtolower($version);
        $alphaQualifierPosition = strpos($test, 'alpha-');
        $betaQualifierPosition = strpos($test, 'beta-');
        $betaQualifierPosition2 = strpos($test, '-beta');
        $rcQualifierPosition = strpos($test, 'rc-');
        $rcQualifierPosition2 = strpos($test, '-rc');
        $rcQualifierPosition3 = strpos($test, 'rc');
        $devQualifiedPosition = strpos($test, 'dev');

        if ($alphaQualifierPosition !== false) {
            $betaRevision = substr($test, $alphaQualifierPosition + 6);
            if (!$betaRevision) {
                $betaRevision = 1;
            }
            $test = substr($test, 0, $alphaQualifierPosition) . '.a' . $betaRevision;
        } elseif ($betaQualifierPosition !== false) {
            $betaRevision = substr($test, $betaQualifierPosition + 5);
            if (!$betaRevision) {
                $betaRevision = 1;
            }
            $test = substr($test, 0, $betaQualifierPosition) . '.b' . $betaRevision;
        } elseif ($betaQualifierPosition2 !== false) {
            $betaRevision = substr($test, $betaQualifierPosition2 + 5);

            if (!$betaRevision) {
                $betaRevision = 1;
            }

            $test = substr($test, 0, $betaQualifierPosition2) . '.b' . $betaRevision;
        } elseif ($rcQualifierPosition !== false) {
            $betaRevision = substr($test, $rcQualifierPosition + 5);
            if (!$betaRevision) {
                $betaRevision = 1;
            }
            $test = substr($test, 0, $rcQualifierPosition) . '.rc' . $betaRevision;
        } elseif ($rcQualifierPosition2 !== false) {
            $betaRevision = substr($test, $rcQualifierPosition2 + 3);

            if (!$betaRevision) {
                $betaRevision = 1;
            }

            $test = substr($test, 0, $rcQualifierPosition2) . '.rc' . $betaRevision;
        } elseif ($rcQualifierPosition3 !== false) {
            $betaRevision = substr($test, $rcQualifierPosition3 + 5);

            if (!$betaRevision) {
                $betaRevision = 1;
            }

            $test = substr($test, 0, $rcQualifierPosition3) . '.rc' . $betaRevision;
        } elseif ($devQualifiedPosition !== false) {
            $betaRevision = substr($test, $devQualifiedPosition + 6);
            if (!$betaRevision) {
                $betaRevision = '';
            }
            $test = substr($test, 0, $devQualifiedPosition) . '.dev' . $betaRevision;
        }

        return $test;
    }

    public function getStability($version)
    {
        $versionParts = explode('.', $version);
        $lastVersionPart = array_pop($versionParts);

        if (substr($lastVersionPart, 0, 1) == 'a') {
            return 'alpha';
        }

        if (substr($lastVersionPart, 0, 1) == 'b') {
            return 'beta';
        }

        if (substr($lastVersionPart, 0, 2) == 'rc') {
            return 'rc';
        }

        if (substr($lastVersionPart, 0, 3) == 'dev') {
            return 'alpha';
        }

        return 'stable';
    }

    /**
     * Checks if there is an update by comparing the version numbers using version_compare()
     *
     * @param string $version
     *
     * @return  bool
     */
    private function hasUpdateByVersion($version)
    {
        $mine = $this->currentVersion;

        if (empty($mine)) {
            $mine = '0.0.0';
        }

        if (empty($version)) {
            $version = '0.0.0';
        }

        return version_compare($version, $mine, 'gt');
    }
}
