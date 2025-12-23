<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use function YOOtheme\App;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;
use ZOOlanders\YOOessentials\Util;

return [
    'transforms' => [
        'render' => function (object $node, array $params) {
            /** @var FormSubmissionRequest $submission */
            $submission = app(FormSubmissionRequest::class);

            $controlName = $node->controls->checkbox['name'];
            $controlProps = $node->controls->checkbox['props'];
            $defaultValue = $controlProps['value'] ?? '';

            $id = $controlProps['id'] ?? null;
            $inheritId = $controlProps->props['id_inherit'] ?? false;

            $fieldset = Util\Node::findClosestType($params, 'yooessentials_form_fieldset');

            if ($fieldset && !$fieldset->props['fields_show_label']) {
                $controlProps['label'] = '';
            }

            if ($inheritId && empty($id)) {
                $id = $controlName;
            }

            $node->control = (object) [
                'id' => $id,
                'name' => $controlName,
                'errors' => $submission->validator()->errors($controlName) ?? [],
                'value' => Arr::wrap($submission->data($controlName) ?? $defaultValue),
                'props' => $controlProps,
            ];
        },
    ],

    'controls' => [
        'checkbox' => function ($node) {
            $props = Util\Prop::filterByPrefix($node->props, 'control_');
            $options = array_map(fn ($child) => $child->props['value'], $node->children);

            return ['props' => $props, 'options' => $options];
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

        $min = (int) ($props['min'] ?? null);
        $max = (int) ($props['max'] ?? null);

        if ($min) {
            $validator->length($min);
        }

        if ($max) {
            $validator->length(null, $max);
        }

        return $validator;
    },
];
