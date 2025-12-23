<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

interface GoogleCalendarApiInterface
{
    public function calendarList(array $args = []): array;

    public function calendar(string $calendarId): array;

    public function events(string $calendarId, array $args = []): array;

    public function event(string $calendarId, string $eventId, array $args = []): array;
}
