<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Mailchimp;

use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class MailchimpActionRemoveMember extends StandardAction
{
    use HasApiRequest;
    use HasValidation;

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();

        try {
            self::validateConfig($config);

            $api = self::api($config->auth);
            $memberId = md5($config->member_email_address);

            if ($config->delete ?? false) {
                $api->deleteListMember($config->list, $memberId);
            } else {
                $api->archiveListMember($config->list, $memberId);
            }
        } catch (\Throwable $e) {
            $error = self::mapError($e->getMessage(), $config) ?: $e->getMessage();

            throw ActionRuntimeException::create($this, $error, $e);
        }

        return $next($response);
    }
}
