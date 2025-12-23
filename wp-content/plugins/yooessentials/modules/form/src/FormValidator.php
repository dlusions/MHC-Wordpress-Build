<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;

class FormValidator
{
    protected FormSubmissionRequest $submission;

    protected ?array $errors = null;

    protected FormService $formService;

    /**
     * Constructor.
     */
    public function __construct(FormSubmissionRequest $submission, FormService $formService)
    {
        $this->submission = $submission;
        $this->formService = $formService;
    }

    public function validate(): bool
    {
        if (!$this->submission->form()) {
            return true;
        }

        if ($this->errors !== null) {
            return empty($this->errors);
        }

        $this->errors = $this->formService->validateElements($this->submission);

        return empty($this->errors);
    }

    public function errors(?string $control = null): array
    {
        if ($control) {
            return $this->errors[$control] ?? [];
        }

        return $this->errors ?? [];
    }
}
