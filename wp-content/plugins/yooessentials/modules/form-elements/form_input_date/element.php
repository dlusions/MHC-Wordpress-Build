<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;

$input = require __DIR__ . '/../form_input_text/element.php';

return array_merge($input, [
    'transforms' => [
        'render' => function (object $node, array $params) use ($input) {
            $input['transforms']['render']($node, $params);

            try {
                $value = $node->control->value ?? null;
                if (is_string($value)) {
                    $value = new \DateTime($value);
                    $node->control->value = $value->format('Y-m-d');
                }

                $value = $node->control->props['mindate'] ?? null;
                if (is_string($value)) {
                    $value = new \DateTime($value);
                    $node->control->props['mindate'] = $value->format('Y-m-d');
                }

                $value = $node->control->props['maxdate'] ?? null;
                if (is_string($value)) {
                    $value = new \DateTime($value);
                    $node->control->props['maxdate'] = $value->format('Y-m-d');
                }
            } catch (\Throwable $e) {
            }
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

        $min = $props['mindate'] ?? null;
        $max = $props['maxdate'] ?? null;

        $validator->dateTime();

        if ($min) {
            $validator->min(new \DateTime($min));
        }

        if ($max) {
            $validator->max(new \DateTime($max));
        }

        return $validator;
    },
]);
