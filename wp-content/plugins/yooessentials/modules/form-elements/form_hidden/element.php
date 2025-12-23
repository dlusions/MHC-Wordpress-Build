<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use ZOOlanders\YOOessentials\Encryption\Encrypter;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Util\Prop;
use function YOOtheme\app;

return [
    'transforms' => [
        'render' => function ($node) {
            $controlName = $node->controls->hidden['name'];
            $controlProps = $node->controls->hidden['props'];

            $controlValue = $controlProps['value'];

            if ($controlProps['encrypt'] ?? false) {
                /** @var Encrypter $encrypter */
                $encrypter = app(Encrypter::class);
                $controlValue = $encrypter->encrypt($controlValue);
            }

            $node->control = (object) [
                'name' => $controlName,
                'id' => $controlProps['id'] ?? null,
                'value' => $controlValue,
                'props' => $controlProps,
            ];
        },
    ],

    'controls' => [
        'hidden' => function ($node) {
            $props = Prop::filterByPrefix($node->props, 'control_');

            return ['props' => $props];
        },
    ],

    'submission' => function ($control, FormSubmissionRequest $submission, FormSubmissionResponse $response) {
        $controlName = $control['name'];
        $controlProps = $control['props'];

        if (!($controlProps['encrypt'] ?? false)) {
            return;
        }

        /** @var Encrypter $encrypter */
        $encrypter = app(Encrypter::class);

        $data = $submission->data();
        $data[$controlName] = $encrypter->decrypt($data[$controlName]) ?: $data[$controlName];
        $submission->setData($data);
    },
];
