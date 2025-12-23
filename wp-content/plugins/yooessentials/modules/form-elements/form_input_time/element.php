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
                if ($value = $node->control->value ?? null) {
                    $value = new \DateTime($value);
                    $node->control->value = $value->format('H:i');
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

        if (strlen($value) <= 2) {
            $value .= ':00';
        }

        $value = \DateTime::createFromFormat('H:i', $value);

        $validator->time('H:i');

        $min = $props['mintime'] ?? null;
        $max = $props['maxtime'] ?? null;

        if ($min) {
            if (strlen($min) <= 2) {
                $min .= ':00';
            }

            $min = \DateTime::createFromFormat('H:i', $min);

            $validator->min($min);
        }

        if ($max) {
            if (strlen($max) <= 2) {
                $max .= ':00';
            }

            $max = \DateTime::createFromFormat('H:i', $max);

            $validator->max($max);
        }

        return $validator;
    },
]);
