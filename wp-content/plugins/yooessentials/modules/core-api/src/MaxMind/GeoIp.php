<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\MaxMind;

use YOOtheme\File;
use YOOtheme\Path;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Vendor\GeoIp2\Database\Reader;
use ZOOlanders\YOOessentials\Vendor\GeoIp2\Exception\AddressNotFoundException;

class GeoIp
{
    public ?Reader $reader = null;

    /**
     * Records for IP addresses already looked up
     */
    private array $lookups = [];

    /**
     * City records for IP addresses already looked up
     */
    private array $cityLookups = [];

    /**
     * Do I have a database with city-level information?
     */
    private bool $hasCity = false;

    public function __construct(Config $config)
    {
        if (!function_exists('bcadd') || !function_exists('bcmul') || !function_exists('bcpow')) {
            require_once __DIR__ . '/fakebcmath.php';
        }

        $dbpath = $config->get('core.geoipdb') ?? '~theme/GeoLite2-City.mmdb';

        if (!File::exists($dbpath) || !File::isFile($dbpath)) {
            return;
        }

        if (Str::contains($dbpath, ['City', 'city'])) {
            $this->hasCity = true;
        }

        try {
            $this->reader = new Reader(Path::get($dbpath));
        } catch (\Throwable $e) {
            // If anything goes wrong, MaxMind will raise an exception, resulting in a WSOD. Let's be sure to catch everything
            $this->reader = null;
        }
    }

    public function isReaderReady(): bool
    {
        return (bool) $this->reader;
    }

    public function isCountryDb(): bool
    {
        return $this->isReaderReady() && !$this->hasCity;
    }

    public function isCityDb(): bool
    {
        return $this->isReaderReady() && $this->hasCity;
    }

    /**
     * Gets a raw country record from an IP address
     *
     * @return mixed A \GeoIp2\Model\Country record if found, false if the IP address is not found, null if the db can't be loaded
     */
    public function getCountryRecord(string $ip)
    {
        if ($this->hasCity) {
            return $this->getCityRecord($ip);
        }

        if (!array_key_exists($ip, $this->lookups)) {
            try {
                $this->lookups[$ip] = null;

                if (!is_null($this->reader)) {
                    $this->lookups[$ip] = $this->reader->country($ip);
                }
            } catch (AddressNotFoundException $e) {
                $this->lookups[$ip] = false;
            } catch (\Throwable $e) {
                // GeoIp2 could throw several different types of exceptions. Let's be sure that we're going to catch them all
                $this->lookups[$ip] = null;
            }
        }

        return $this->lookups[$ip];
    }

    /**
     * Gets a raw city record from an IP address
     *
     * @param string $ip The IP address to look up
     *
     * @return mixed A \GeoIp2\Model\City record if found, false if the IP address is not found, null if the db can't be loaded
     */
    public function getCityRecord(string $ip)
    {
        if (!$this->hasCity) {
            return null;
        }

        $needsToLoad = !array_key_exists($ip, $this->cityLookups);

        if ($needsToLoad) {
            try {
                if (!is_null($this->reader)) {
                    $this->cityLookups[$ip] = $this->reader->city($ip);
                } else {
                    $this->cityLookups[$ip] = null;
                }
            } catch (AddressNotFoundException $e) {
                $this->cityLookups[$ip] = false;
            } catch (\Throwable $e) {
                // GeoIp2 could throw several different types of exceptions. Let's be sure that we're going to catch them all
                $this->cityLookups[$ip] = null;
            }
        }

        return $this->cityLookups[$ip];
    }
}
