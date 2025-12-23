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

            $name = $node->controls->frcaptcha['name'];
            $props = $node->controls->frcaptcha['props'];

            $lang = $props['language'] ?? false;
            $siteKey = $props['site_key'] ?? false;
            $secretKey = $props['secret_key'] ?? false;
            $endpoint = $props['endpoint'] ?? 'global';

            if (empty($lang)) {
                $code = str_replace('_', '-', app()->config->get('locale.code'));
                $lang = explode('-', $code)[0] ?? 'en';
            }

            if (!$siteKey or !$secretKey) {
                return false;
            }

            $node->control = (object) [
                'name' => $name,
                'errors' => $submission->validator()->errors($name) ?? [],
                'value' => $submission->data($name),
                'props' => $props,
                'lang' => $lang,
                'siteKey' => $siteKey,
                'secretKey' => $secretKey,
                'endpoint' => $endpoint,
            ];
        },
    ],

    'controls' => [
        'frcaptcha' => function ($node) {
            $props = Prop::filterByPrefix($node->props, 'control_');
            $name = 'frc-captcha-solution';

            return ['name' => $name, 'props' => $props];
        },
    ],

    'validation' => function ($control, Validator $validator) {
        $validator->setName('Captcha');

        $validator->callback(function ($value) use ($control, $validator) {
            /** @var HttpClientInterface $client */
            $client = app(HttpClientInterface::class);

            $secretKey = $control['props']['secret_key'] ?? '';
            $siteKey = $control['props']['site_key'] ?? '';
            $endpoint = $control['props']['endpoint'] ?? 'global';
            $errorMessage = $control['props']['error_message'] ?? '';

            $endpoints = [
                'global' => 'https://api.friendlycaptcha.com/api/v1/siteverify',
                'eu' => 'https://eu-api.friendlycaptcha.eu/api/v1/siteverify',
            ];

            try {
                if (!$secretKey) {
                    throw new \Exception('Missing Secret Key');
                }

                /** @var Response $response */
                $response = $client->post($endpoints[$endpoint], [
                    'secret' => $secretKey,
                    'sitekey' => $siteKey,
                    'solution' => $value,
                ]);
            } catch (\Throwable $e) {
                $validator->setTemplate($errorMessage || 'Captcha Validation Error: ' . $e->getMessage());

                return false;
            }

            $reply = json_decode($response->getBody(), true);

            return $reply['success'] ?? false;
        });

        return $validator;
    },

    'submission' => function ($control, FormSubmissionRequest $submission, FormSubmissionResponse $response) {
        $controlName = 'frc-captcha-solution';

        // remove from data, it's not used
        $data = $submission->data();
        unset($data[$controlName]);
        $submission->setData($data);
    },
];
