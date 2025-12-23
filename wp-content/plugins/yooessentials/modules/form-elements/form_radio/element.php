<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use function YOOtheme\App;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;

return [
    'transforms' => [
        'render' => function (object $node, array $params) {
            /** @var FormSubmissionRequest $submission */
            $submission = app(FormSubmissionRequest::class);

            $controlName = $node->controls->radio['name'];
            $controlProps = $node->controls->radio['props'];

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
                'value' => $submission->data($controlName) ?? $controlProps['value'],
                'props' => $controlProps,
            ];
        },
    ],

    'controls' => [
        'radio' => function ($node) {
            $props = Util\Prop::filterByPrefix($node->props, 'control_');
            $options = array_map(fn ($child) => $child->props['value'], $node->children);

            return ['props' => $props, 'options' => $options];
        },
    ],

    'validation' => function ($control, Validator $validator) {
        $props = $control['props'] ?? [];
        $required = $props['required'] ?? false;

        if ($required) {
            $validator->notOptional();
        }

        return $validator;
    },
];
