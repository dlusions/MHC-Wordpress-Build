<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Controller;

use YOOtheme\Builder\Source;

use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Form\FormService;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Session;

class FormController
{
    protected FormService $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    // Source param is needed to init the source
    public function submit(FormSubmissionRequest $submission, Response $response, Session $session, Source $source)
    {
        $response = new FormSubmissionResponse($submission, $response);

        if ($response->hasErrors()) {
            $session->set('yooessentials.submission', $submission->toArray());

            return $response->respond();
        }

        $response = $submission->process($response);

        // Save the form even when an action has failed
        if ($response->hasErrors()) {
            $session->set('yooessentials.submission', $submission->toArray());
        }

        return $response->respond();
    }
}
