<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

// https://developers.google.com/calendar
class GoogleCalendarApi extends GoogleApi implements GoogleCalendarApiInterface
{
    protected string $apiEndpoint = 'https://www.googleapis.com/calendar/v3';

    public function calendarList(array $args = []): array
    {
        $result = $this->get('users/me/calendarList', array_merge($args, []));

        return $result['items'] ?? [];
    }

    public function calendar(string $calendarId): array
    {
        return $this->get("calendars/$calendarId");
    }

    public function events(string $calendarId, array $args = []): array
    {
        $datetimeArgs = ['timeMin', 'timeMax', 'updatedMin'];
        $eventTypes = ['default', 'focusTime', 'outOfOffice'];
        $boolenArgs = ['singleEvents', 'showDeleted', 'alwaysIncludeEmail', 'showHiddenInvitations', 'prettyPrint'];

        $args['eventTypes'] = $eventTypes;

        // cast args
        foreach ($args as $key => $value) {
            if (in_array($key, $boolenArgs)) {
                $args[$key] = $value ? 'true' : 'false';
            }

            if (in_array($key, $datetimeArgs)) {
                $args[$key] = (new \DateTime($value))->format('c');
            }
        }

        // order by start is only available when singleEvents is true
        if (($args['orderBy'] ?? '') === 'startTime' && !isset($args['singleEvents'])) {
            unset($args['orderBy']);
        }

        // remove empty values
        $args = array_filter($args);

        $result = $this->get("calendars/$calendarId/events", $args);

        return $result['items'] ?? [];
    }

    public function event(string $calendarId, string $eventId, array $args = []): array
    {
        return $this->get("calendars/$calendarId/events/$eventId", $args);
    }
}
