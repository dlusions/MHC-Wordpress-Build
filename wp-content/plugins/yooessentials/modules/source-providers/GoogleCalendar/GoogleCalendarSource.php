<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleCalendar;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Api\Google\GoogleCalendarApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;

class GoogleCalendarSource extends AbstractSourceType
{
    private ?GoogleCalendarApiInterface $api = null;

    public function types(): array
    {
        return [
            new Type\GoogleCalendarEventType(),
            new Type\GoogleCalendarProfileType(),
            new Type\GoogleCalendarAttendeeType(),
            new Type\GoogleCalendarCalendarType(),
            new Type\GoogleCalendarAttachmentType(),
            new Type\GoogleCalendarEventQueryType($this),
            new Type\GoogleCalendarEventsQueryType($this),
            new Type\GoogleCalendarCalendarQueryType($this),
        ];
    }

    public function auth(): ?AuthOAuth
    {
        $account = $this->config()['account'] ?? null;

        if (!$account) {
            throw new \Exception('Auth Account must be specified.');
        }

        return app(AuthManager::class)->auth($account);
    }

    public function api(): GoogleCalendarApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        $this->api = app(GoogleCalendarApiInterface::class);

        try {
            $this->api->withAccessToken($this->auth()->accessToken());
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'google-calendar',
                'error' => $e->getMessage(),
            ]);
        }

        return $this->api;
    }
}
