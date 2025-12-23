<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use YOOtheme\View;
use YOOtheme\Builder;
use ZOOlanders\YOOessentials\Migration;

require_once __DIR__ . '/class_aliases.php';

return [
    'routes' => [
        ['get', Action\ActionController::ACTIONS_URL, Action\ActionController::class . '@actions', ['allowed' => true]],
        ['post', Http\FormSubmissionRequest::SUBMIT_URL, Controller\FormController::class . '@submit', ['allowed' => true]],
        [
            'post',
            Controller\FormAdminController::FIELDS_URL,
            Controller\FormAdminController::class . '@fields',
            ['allowed' => true],
        ],
    ],

    'events' => [
        Http\FormSubmissionRequest::SUBMISSION_EVENT => [
            Action\Listener\HandleFormActions::class => ['process', -10],
        ],

        'source.init' => [
            Listener\AddFormSource::class => ['@handle', 66],
        ],

        'customizer.init' => [
            Action\Listener\LoadCustomizerData::class => ['@handle', 5],
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'builder.type' => [
            Listener\AddFormPanel::class => ['@handle', -10],
        ],
    ],

    'extend' => [
        Builder::class => function (Builder $builder, $app) {
            $formIdTransform = $app(FormIdTransform::class);

            $builder->addTransform('presave', [$formIdTransform, 'presave']);
            $builder->addTransform('preload', [$formIdTransform, 'preload']);
            $builder->addTransform('render', new ControlTransform());
            $builder->addTransform('render', new FormTransform($builder, $app(FormConfigRepository::class)));
        },

        View::class => function (View $view) {
            $view->addHelper(Html\HtmlHelper::class);
        },
    ],

    'yooessentials-migrations' => [
        Migration\Migration150\MigrateFormActions::class,
        Migration\Migration180\FixFormUploadElementUploadPath::class,
        Migration\Migration180\MigrateFormActions::class,
        Migration\Migration200\MigrateDatabaseActionConnections::class,
        Migration\Migration220\FixFormFieldsetRegression::class,
        Migration\Migration230\MigrateFormMultioptionFields::class,
        Migration\Migration230\RefactorFormFieldsetAsSublayout::class,
        Migration\Migration230\RenameFormActions::class,
        Migration\Migration240\MigrateDatabaseActions::class,
    ],

    'services' => [
        FormService::class => '',
        FormConfigRepository::class => '',
        Action\ActionManager::class => '',
        Http\FormSubmissionRequest::class => '',
    ],

    'loaders' => [
        'yooessentials-form-actions' => new Action\ActionLoader(),
    ],
];
