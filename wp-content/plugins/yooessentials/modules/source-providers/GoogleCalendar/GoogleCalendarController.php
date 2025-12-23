<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar;

use function YOOtheme\app;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\Google\GoogleCalendarApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class GoogleCalendarController
{
    use LoadsSourceFromArgs;

    /** @var string */
    public const PRESAVE_ENDPOINT = 'yooessentials/source/google/calendar';

    /** @var string */
    public const GET_EVENTS_ENDPOINT = 'yooessentials/source/google/calendar/events';

    /** @var string */
    public const GET_CALENDARS_ENDPOINT = 'yooessentials/source/google/calendar/calendars';

    public function presave(Request $request, Response $response)
    {
        $form = $request('form');
        $account = $form['account'] ?? null;
        $calendar = $form['calendar'] ?? null;

        if (!$account) {
            return $response->withJson('Account must be specified.', 400);
        }

        if (!$calendar) {
            return $response->withJson('Calendar must be specified.', 400);
        }

        return $response->withJson(200);
    }

    public function calendars(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $query = $request->getParam('query') ?? null;

        try {
            $api = self::initApi($form);

            $items = array_map(fn ($calendar) => [
                'text' => $calendar['summary'],
                'value' => $calendar['id'],
                'meta' => sprintf('id: %s tz: %s', $calendar['id'], $calendar['timeZone']),
            ], $api->calendarList());

            if ($query) {
                $items = array_values(
                    array_filter($items, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text']))
                );
            }

            return $response->withJson($items, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function events(Request $request, Response $response)
    {
        $sourceId = $request->getParam('source_id') ?? [];
        $query = $request->getParam('query') ?? null;

        try {
            $source = self::loadSource([
                'source_id' => $sourceId
            ], GoogleCalendarSource::class);

            $events = $source->api()->events($source->config('calendar'), [
                'q' => $query
            ]);

            $events = array_map(fn ($page) => [
                'text' => $page['summary'] ?? $page['id'],
                'value' => $page['id'],
                'meta' => $page['id'],
            ], $events);

            return $response->withJson($events, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    protected static function initApi(array $form): GoogleCalendarApiInterface
    {
        $authManager = app(AuthManager::class);

        $account = $form['account'] ?? null;

        if (!$account) {
            throw new \Exception('Account must be specified.');
        }

        $auth = $authManager->auth($account);

        if (!$auth) {
            throw new \Exception('Invalid Auth.');
        }

        if (!$auth->accessToken ?? false) {
            throw new \Exception('Token is invalid.');
        }

        return app(GoogleCalendarApiInterface::class)->withAccessToken($auth->accessToken());
    }
}
