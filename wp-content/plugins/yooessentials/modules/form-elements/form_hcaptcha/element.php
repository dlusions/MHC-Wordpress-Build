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
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Util\Prop;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;

return [
    'transforms' => [
        'render' => function ($node) {
            /** @var FormSubmissionRequest $submission */
            $submission = app(FormSubmissionRequest::class);

            $name = $node->controls->hcaptcha['name'];
            $props = $node->controls->hcaptcha['props'];

            $siteKey = $props['site_key'] ?? false;
            $secretKey = $props['secret_key'] ?? false;

            if (!$siteKey or !$secretKey) {
                return false;
            }

            $node->control = (object) [
                'name' => $name,
                'errors' => $submission->validator()->errors($name) ?? [],
                'value' => $submission->data($name),
                'props' => $props,
                'siteKey' => $siteKey,
                'secretKey' => $secretKey,
            ];
        },
    ],

    'controls' => [
        'hcaptcha' => function ($node) {
            $props = Prop::filterByPrefix($node->props, 'control_');
            $name = 'h-captcha-response';

            return ['name' => $name, 'props' => $props];
        },
    ],

    'validation' => function ($control, Validator $validator) {
        $validator->setName('Captcha');

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
                $response = $client->post('https://hcaptcha.com/siteverify', [
                    'secret' => $secretKey,
                    'response' => $value,
                ]);
            } catch (\Throwable $e) {
                $validator->setTemplate($errorMessage || 'Captcha Validation Error: ' . $e->getMessage());

                return false;
            }

            $reply = json_decode($response->getBody(), true);

            $threshold = (float) ($control['props']['threshold'] ?? 0.5);
            $score = $reply['score'] ?? 0;

            if ($score >= $threshold) {
                return false;
            }

            return $reply['success'] ?? false;
        });

        return $validator;
    },

    'submission' => function ($control, FormSubmissionRequest $submission, FormSubmissionResponse $response) {
        $controlName = 'h-captcha-response';

        // Remove from data, it's not used
        $data = $submission->data();
        unset($data[$controlName]);
        $submission->setData($data);
    },
];
