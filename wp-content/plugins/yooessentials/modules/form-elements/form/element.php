<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use YOOtheme\Builder\ElementTransform;
use YOOtheme\Metadata;
use YOOtheme\Url;
use YOOtheme\View;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Util;
use function YOOtheme\App;

return [
    'transforms' => [

        'render' => function ($node) {
            /** @var FormSubmissionRequest $submission */
            $submission = app(FormSubmissionRequest::class);

            /** @var Metadata $submission */
            $metadata = app(Metadata::class);

            /** @var View $view */
            $view = app(View::class);

            /** @var ElementTransform $transform */
            $transform = new ElementTransform($view);

            /** @var FormService $formService */
            $formService = app(FormService::class);

            if (!isset($node->id)) {
                return;
            }

            $form = $formService->loadForm($node->id);
            $formConfig = $form->config();

            // Empty config means that cache is empty and this is first rendering
            if (empty($formConfig)) {
                $formConfig = (array) ($node->yooessentials_form ?? []);
                $formService->saveConfig($node->id, $formConfig);
            }

            $hasExternalActionUrl = $form->hasExternalActionUrl();
            $submitUrl = Url::route(FormSubmissionRequest::SUBMIT_URL);

            $node->form = new \stdClass();
            $node->form->csrf = $submission->csrfFormToken;

            // set attributes
            $node->props['attributes'] = $formConfig['attributes'] ?? '';

            $config = [
                'html5validation' => $formConfig['html5validation'] ?? true,
                'reset_after_submit' => $formConfig['reset_after_submit'] ?? true,
                'errors_display' => (object) array_filter([
                    'modal' => $formConfig['errors_display_modal'] ?? false,
                    'modal_content' => $formConfig['errors_display_modal_content'] ?? false,
                    'modal_center' => $formConfig['errors_display_modal_center'] ?? false,
                ]),
            ];

            $node->attrs += [
                'id' => $formConfig['id'] ?? null,
                'class' => ['uk-form', $formConfig['class'] ?? ''],
                'name' => $formConfig['name'] ?? null,
                'validate-action' => '',
                'action' => $submitUrl,
                'method' => 'POST',
                'data-uk-yooessentials-form' => json_encode(['config' => $config]),
                'data-yooessentials-formid' => $node->id,
                'novalidate' => true,

            ];

            if ($hasExternalActionUrl) {
                $node->attrs['validate-action'] = $submitUrl;
                $node->attrs['action'] = $formConfig['action_url'] ?? '';
                $node->attrs['method'] = $formConfig['action_method'] ?? 'POST';
            }

            // apply attributes transforms
            $transform->customAttributes($node);

            $scripts = Util\Prop::filterByPrefix($formConfig, 'scripts_');

            $node->form->hooks = $scripts;

            $metadata->set('script:yooessentials-form', [
                'src' => '~yooessentials_url/modules/form/assets/form.min.js',
                'defer' => true,
            ]);
        },
    ],
];
