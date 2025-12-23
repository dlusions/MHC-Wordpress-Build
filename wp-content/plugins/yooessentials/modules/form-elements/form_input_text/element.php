<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use function YOOtheme\App;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Util\Prop;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;

return [
    'transforms' => [
        'render' => function (object $node) {
            /** @var FormSubmissionRequest $submission */
            $submission = app(FormSubmissionRequest::class);

            $controlName = $node->controls->input['name'];
            $controlProps = $node->controls->input['props'];

            $node->control = (object) [
                'name' => $controlName,
                'id' => $controlProps['id'] ?? null,
                'errors' => $submission->validator()->errors($controlName) ?? [],
                'value' => $submission->data($controlName) ?? $controlProps['value'],
                'props' => $controlProps,
            ];
        },
    ],

    'controls' => [
        'input' => function ($node, array $params) {
            $index = $params['i'] ?? 0;
            $parent = $params['parent'];
            $props = Prop::filterByPrefix($node->props, 'control_');

            return ['props' => $props];
        },
    ],

    'validation' => function ($control, Validator $validator) {
        $props = $control['props'] ?? [];
        $value = $control['value'] ?? '';
        $required = $props['required'] ?? false;

        if ($required) {
            $validator->notOptional();
        }

        if ($value === '') {
            return $validator;
        }

        $min = $props['minlength'] ?? null;
        $max = $props['maxlength'] ?? null;
        $pattern = $props['pattern'] ?? null;

        if ($min) {
            $validator->length($min);
        }

        if ($max) {
            $validator->length(null, $max);
        }

        if ($pattern) {
            $validator->regex("/$pattern/");
        }

        return $validator;
    },
];
