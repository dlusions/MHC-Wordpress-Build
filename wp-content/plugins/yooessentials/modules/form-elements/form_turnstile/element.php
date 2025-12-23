<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use function YOOtheme\App;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\HttpClientInterface;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Util\Prop;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;

return [
    'transforms' => [
        'render' => function ($node) {
            /** @var FormSubmissionRequest $submission */
            $submission = app(FormSubmissionRequest::class);

            $name = $node->controls->turnstile['name'];
            $props = $node->controls->turnstile['props'];

            $sitekey = $props['sitekey'] ?? false;
            $secretKey = $props['secret_key'] ?? false;

            if (!$sitekey or !$secretKey) {
                return false;
            }

            $node->control = (object) [
                'name' => $name,
                'errors' => $submission->validator()->errors($name) ?? [],
                'value' => $submission->data($name),
                'props' => $props,
                'sitekey' => $sitekey,
                'secretKey' => $secretKey,
            ];
        },
    ],

    'controls' => [
        'turnstile' => function ($node) {
            $props = Prop::filterByPrefix($node->props, 'control_');
            $name = 'cf-turnstile-response';

            return ['name' => $name, 'props' => $props];
        },
    ],

    'validation' => function ($control, Validator $validator) {
        $validator->setName('Turnstile');

        $validator->callback(function ($value) use ($control, $validator) {
            /** @var HttpClientInterface $client */
            $client = app(HttpClientInterface::class);

            $secretKey = $control['props']['secret_key'] ?? '';
            $errorMessage = $control['props']['error_message'] ?? '';

            try {
                if (!$secretKey) {
                    throw new \Exception('Missing Secret Key');
                }

                /** @var Response $response */
                $response = $client->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secretKey,
                    'response' => $value,
                ]);
            } catch (\Throwable $e) {
                $validator->setTemplate($errorMessage || 'Turnstile Validation Error: ' . $e->getMessage());

                return false;
            }

            $reply = json_decode($response->getBody(), true);

            return $reply['success'] ?? false;
        });

        return $validator;
    },

    'submission' => function ($control, FormSubmissionRequest $submission) {
        $controlName = 'cf-turnstile-response';

        // Remove from data, it's not used
        $data = $submission->data();
        unset($data[$controlName]);
        $submission->setData($data);
    },
];
