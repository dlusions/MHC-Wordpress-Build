<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

/**
 * Code adapted from Regular Labs Library version 20.9.11663
 *
 * @author Peter van Westen
 * @copyright Copyright © 2020 Regular Labs All Rights Reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
abstract class Ip
{
    private static $fakeIp = null;

    public static function checkIP($range)
    {
        if (empty($range)) {
            return false;
        }

        if (strpos($range, '-') !== false) {
            // Selection is an IP range
            return self::checkIPRange($range);
        }

        // Selection is a single IP (part)
        return self::checkIPPart($range);
    }

    private static function checkIPRange($range)
    {
        $ip = self::getIP();

        // Return if no IP address can be found (shouldn't happen, but who knows)
        if (empty($ip)) {
            return false;
        }

        // check if IP is between or equal to the from and to IP range
        list($min, $max) = explode('-', trim($range), 2);

        // Return false if IP is smaller than the range start
        if ($ip < trim($min)) {
            return false;
        }

        $max = self::fillMaxRange($max, $min);

        // Return false if IP is larger than the range end
        if ($ip > trim($max)) {
            return false;
        }

        return true;
    }

    /**
     * Fill the max range by prefixing it with the missing parts from the min range
     * So 101.102.103.104-201.202 becomes:
     * max: 101.102.201.202
     */
    private static function fillMaxRange($max, $min)
    {
        $max_parts = explode('.', $max);

        if (count($max_parts) === 4) {
            return $max;
        }

        $min_parts = explode('.', $min);

        $prefix = array_slice($min_parts, 0, count($min_parts) - count($max_parts));

        return trim(implode('.', $prefix) . '.' . implode('.', $max_parts), '.');
    }

    private static function checkIPPart($range)
    {
        $ip = self::getIP();

        if (empty($ip)) {
            return false;
        }

        $ip_parts = explode('.', $ip);
        $range_parts = explode('.', trim($range));

        // Trim the IP to the part length of the range
        $ip = implode('.', array_slice($ip_parts, 0, count($range_parts)));

        // Return false if ip does not match the range
        if ($range != $ip) {
            return false;
        }

        return true;
    }

    public static function getIP()
    {
        if (self::$fakeIp !== null) {
            return self::$fakeIp;
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && self::isValidIp($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (!empty($_SERVER['HTTP_X_REAL_IP']) && self::isValidIp($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP']) && self::isValidIp($_SERVER['HTTP_CLIENT_IP'])) {
            $_SERVER['HTTP_CLIENT_IP'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    private static function isValidIp($string)
    {
        return preg_match('#^([0-9]{1,3}\.){3}[0-9]{1,3}$#', $string);
    }

    public static function setFakeIp(?string $ip): void
    {
        self::$fakeIp = $ip;
    }
}
