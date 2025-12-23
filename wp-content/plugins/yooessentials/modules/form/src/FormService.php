<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use YOOtheme\Arr;
use YOOtheme\Builder;
use YOOtheme\Config;
use ZOOlanders\YOOessentials\Form\Action\Action;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Exceptions\ValidationException;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;

class FormService
{
    protected bool $enabled = true;

    public array $actions = [];

    /** @var callable */
    protected $loader;

    protected Builder $builder;

    private FormConfigRepository $configRepository;

    public function __construct(Config $config, FormConfigRepository $configRepository, Builder $builder)
    {
        $this->loader = [$config, 'loadFile'];
        $this->builder = $builder;
        $this->configRepository = $configRepository;
    }

    public function loadForm(string $id): Form
    {
        $config = $this->loadConfig($id);

        return new Form($id, $config);
    }

    /**
     * Load the Config for a form
     * @param  string  $formId
     * @return array
     */
    public function loadConfig(string $formId): array
    {
        return $this->configRepository->loadConfig($formId);
    }

    /**
     * Save config for a form
     * @param  string  $id
     * @param  array  $config
     */
    public function saveConfig(string $id, array $config): void
    {
        $this->configRepository->saveConfig($id, $config);
    }

    /**
     * Save config for a form in the old cache
     * @param  string  $id
     * @param  array  $config
     */
    public function saveOldConfig(string $id, array $config): void
    {
        $this->configRepository->saveOldConfig($id, $config);
    }

    public function addAction(Action $action): self
    {
        if (!isset($this->actions[$action->name()])) {
            $this->actions[$action->name()] = $action;
        }

        return $this;
    }

    public function actions(): array
    {
        return $this->actions;
    }

    public function getControlType(array $control): ?object
    {
        return $this->builder->types[$control['type']] ?? null;
    }

    public function getControlList($node): array
    {
        return array_reduce(
            $node->children,
            function ($carry, $node) {
                $type = $this->builder->types[$node->type] ?? null;

                if (!$type) {
                    return $carry;
                }

                if ($type->submittable and ($type->data['controls'] ?? false)) {
                    $props = Util\Prop::filterByPrefix((array) $node->props, 'control_');
                    $carry[] = [
                        'name' => $props['name'],
                        'type' => $node->type,
                    ];
                }

                if ($node->children ?? false) {
                    $carry = array_merge($carry, $this->getControlList($node));
                }

                return $carry;
            },
            []
        );
    }

    public function validateElements(FormSubmissionRequest $submission): array
    {
        $errors = [];

        foreach ($submission->config()['controls'] ?? [] as $control) {
            $type = $this->getControlType($control);

            if (!$type) {
                continue;
            }

            $validation = $type->data['validation'] ?? null;
            if (!is_callable($validation)) {
                continue;
            }

            $name = $control['name'];
            $props = $control['props'];
            $label = $props['label'] ?? $name;
            $value = $submission->data($name);
            $control['value'] = $value;

            $validator = new Validator();
            $validator->setName($label);

            try {
                $validation($control, $validator, $submission);
                $validator->check($value);
            } catch (ValidationException $exception) {
                if ($message = $props['error_message'] ?? false) {
                    $message = str_replace('{fieldlabel}', $label ?: $name, $message);
                    $exception->updateTemplate($message);
                }

                $errors[$name] = Arr::wrap($exception->getMessage());
            }
        }

        return $errors;
    }

    public function processElementSubmission(
        FormSubmissionRequest $submission,
        FormSubmissionResponse $submissionResponse
    ): void {
        foreach ($submission->form()->config()['controls'] ?? [] as $control) {
            $type = $this->getControlType($control);

            if ($type && is_callable($type->data['submission'] ?? null)) {
                $type->data['submission']($control, $submission, $submissionResponse);
            }
        }
    }

    public static function parseTags(string $content, array $data): string
    {
        foreach ($data as $dataField => $value) {
            if (is_null($value)) {
                continue;
            }

            if (is_array($value)) {
                // if multidimensional array
                if (count($value) !== count($value, COUNT_RECURSIVE)) {
                    $value = json_encode($value);
                } else {
                    $value = implode(', ', $value);
                }
            }

            // replace breaklines with <br>
            $value = nl2br($value);

            $tag = '{' . $dataField . '}';
            $content = str_replace($tag, $value, $content);
        }

        // remove any left out tag, unless is a valid json
        $content = preg_replace_callback(
            '/{[^}\n ]*}/',
            fn ($matches) => json_decode($matches[0]) ? $matches[0] : '',
            $content
        );

        return $content;
    }
}
