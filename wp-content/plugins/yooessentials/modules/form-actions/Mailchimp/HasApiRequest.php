<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Mailchimp;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Mailchimp\MailchimpApiInterface;

trait HasApiRequest
{
    public static function api(string $authId): MailchimpApiInterface
    {
        try {
            $api = app(MailchimpApiInterface::class);
            $auth = app(AuthManager::class)->auth($authId);

            if ($auth->driver()->type() === 'oauth') {
                return $api->withAccessToken($auth->accessToken())->withDataCenter($auth->dc);
            }

            return $api->withApiKey($auth->key);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'form-action',
                'provider' => 'mailchimp',
                'error' => $e->getMessage(),
                'exception' => $e
            ]);

            return app(MailchimpApiInterface::class);
        }
    }
}
