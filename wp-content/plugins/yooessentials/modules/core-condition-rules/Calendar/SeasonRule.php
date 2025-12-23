<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Calendar;

class SeasonRule extends DayRule
{
    public function resolveProps(object $props, object $node): object
    {
        if (!isset($props->seasons)) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        if (empty($props->hemisphere)) {
            $props->hemisphere = '';
        }

        return $props;
    }

    public function resolve($props, $node): bool
    {
        $now = $this->now();
        $season = self::getSeason($now, $props->hemisphere ?? '');

        return in_array($season, (array) $props->seasons);
    }

    public function logArgs(object $props): array
    {
        return [
            'Northern Season' => self::getSeason($this->now()),
            'Southern Season' => self::getSeason($this->now(), 'southern'),
            'Australia Season' => self::getSeason($this->now(), 'australia')
        ];
    }

    /**
     * Code extracted from Regular Labs Library version 20.9.11663
     *
     * @author          Peter van Westen
     * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
     * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
     */
    private static function getSeason($d, string $hemisphere = '')
    {
        // Set $date to today
        $date = strtotime($d->format('Y-m-d H:i:s'));

        // Get year of date specified
        $date_year = $d->format('Y'); // Four digit representation for the year

        // Specify the season names
        $season_names = ['winter', 'spring', 'summer', 'fall'];

        // Declare season date ranges
        switch (strtolower($hemisphere)) {
            case 'southern':
                if ($date < strtotime($date_year . '-03-21') || $date >= strtotime($date_year . '-12-21')) {
                    return $season_names[2]; // Must be in Summer
                }

                if ($date >= strtotime($date_year . '-09-23')) {
                    return $season_names[1]; // Must be in Spring
                }

                if ($date >= strtotime($date_year . '-06-21')) {
                    return $season_names[0]; // Must be in Winter
                }

                if ($date >= strtotime($date_year . '-03-21')) {
                    return $season_names[3]; // Must be in Fall
                }

                break;
            case 'australia':
                if ($date < strtotime($date_year . '-03-01') || $date >= strtotime($date_year . '-12-01')) {
                    return $season_names[2]; // Must be in Summer
                }

                if ($date >= strtotime($date_year . '-09-01')) {
                    return $season_names[1]; // Must be in Spring
                }

                if ($date >= strtotime($date_year . '-06-01')) {
                    return $season_names[0]; // Must be in Winter
                }

                if ($date >= strtotime($date_year . '-03-01')) {
                    return $season_names[3]; // Must be in Fall
                }

                break;
            default:
                // northern
                if ($date < strtotime($date_year . '-03-21') || $date >= strtotime($date_year . '-12-21')) {
                    return $season_names[0]; // Must be in Winter
                }

                if ($date >= strtotime($date_year . '-09-23')) {
                    return $season_names[3]; // Must be in Fall
                }

                if ($date >= strtotime($date_year . '-06-21')) {
                    return $season_names[2]; // Must be in Summer
                }

                if ($date >= strtotime($date_year . '-03-21')) {
                    return $season_names[1]; // Must be in Spring
                }

                break;
        }

        return 0;
    }
}
