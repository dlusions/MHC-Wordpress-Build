<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Email;

use YOOtheme\Http\Request;
use ZOOlanders\YOOessentials\Form\FormService;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class EmailActionController
{
    public const TEST_EMAIL_URL = '/yooessentials/form-send-test-email';

    public function sendTest(Request $request, FormSubmissionResponse $response, FormService $formService)
    {
        $formId = $request->getParam('formid');
        $actionId = $request->getParam('action', 0);

        if (!$formId) {
            return $request->abort(400, 'Form ID not provided.');
        }

        $form = $formService->loadForm($formId);

        if (!$form->hasAction(EmailAction::NAME)) {
            return $request->abort(400, 'Form does not have Email Action.');
        }

        $action = $form->actionConfigs(EmailAction::NAME)[$actionId] ?? null;

        if (!$action) {
            return $request->abort(400, 'Cannot find an action with the given ID:' . $actionId);
        }

        $config = EmailAction::prepareConfig($action->config(), $response);

        if (count($config['recipients']) <= 0) {
            return $request->abort(400, 'Missing Recipient');
        }

        try {
            $mailer = EmailAction::prepareMailer($config);

            if ($mailer->send()) {
                return $response->withJson(200);
            }

            return $response->withJson('Cannot Send Email', 400);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
