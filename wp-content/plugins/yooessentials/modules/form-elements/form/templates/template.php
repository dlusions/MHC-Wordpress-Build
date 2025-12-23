<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use function YOOtheme\app;
use YOOtheme\Metadata;

/** @var Metadata $metadata */
$metadata = app(Metadata::class);

$config = [
    'html5validation' => $node->form->config['html5validation'] ?? true,
    'reset_after_submit' => $node->form->config['reset_after_submit'] ?? true,
    'errors_display' => (object) array_filter([
        'modal' => $node->form->config['errors_display_modal'] ?? false,
        'modal_content' => $node->form->config['errors_display_modal_content'] ?? false,
        'modal_center' => $node->form->config['errors_display_modal_center'] ?? false,
    ]),
];

$form = $this->el('form');

$errors = $this->el(
    'div',
    [
        'class' => ['uk-text-danger', 'uk-text-small'],
        'data-yooessentials-form-errors' => true,
    ],
    ''
);

$scripts = array_filter($node->form->hooks ?? []);

if (isset($scripts['onsubmit'])) {
    $metadata->set(
        "script:yooessentials-form-{$node->id}-onsubmit",
        sprintf(
            "UIkit.util.on(document, 'yooessentials-form:submit', function (e, ctx) {
                var form = ctx.form;
                var data = ctx.data;

                if (form.dataset.yooessentialsFormid === '%s') {
                    const onSubmit = function () {%s};

                    if (onSubmit() === false) {
                        e.preventDefault();
                    }
                }
            });",
            $node->id,
            $scripts['onsubmit']
        ),
        [
            'defer' => true,
            'data-preview' => 'diff'
        ]
    );
}

if (isset($scripts['onsubmitted'])) {
    $metadata->set(
        "script:yooessentials-form-{$node->id}-onsubmitted",
        sprintf(
            "UIkit.util.on(document, 'yooessentials-form:submitted', function (e, ctx) {
                var form = ctx.form;
                var data = ctx.data;
                var response = ctx.response;

                if (form.dataset.yooessentialsFormid === '%s') {
                    %s
                }
            });",
            $node->id,
            $scripts['onsubmitted']
        ),
        [
            'defer' => true,
            'data-preview' => 'diff'
        ]
    );
}

if (isset($scripts['onvalidationerror'])) {
    $metadata->set(
        "script:yooessentials-form-{$node->id}-onvalidationerror",
        sprintf(
            "UIkit.util.on(document, 'yooessentials-form:validation-error', function (e, ctx) {
                var form = ctx.form;
                var data = ctx.data;

                if (form.dataset.yooessentialsFormid === '%s') {
                    %s
                }
            });",
            $node->id,
            $scripts['onvalidationerror']
        ),
        [
            'defer' => true,
            'data-preview' => 'diff'
        ]
    );
}

if (isset($scripts['onsubmissionerror'])) {
    $metadata->set(
        "script:yooessentials-form-{$node->id}-onsubmissionerror",
        sprintf(
            "UIkit.util.on(document, 'yooessentials-form:submission-error', function (e, ctx) {
                var form = ctx.form;
                var data = ctx.data;
                var error = ctx.error;
                var errors = ctx.errors;
                var validation = ctx.validation;

                if (form.dataset.yooessentialsFormid === '%s') {
                    %s
                }
            });",
            $node->id,
            $scripts['onsubmissionerror']
        ),
        [
            'defer' => true,
            'data-preview' => 'diff'
        ]
    );
}

if (isset($scripts['onfieldchange'])) {
    $metadata->set(
        "script:yooessentials-form-{$node->id}-onfieldchange",
        sprintf(
            "UIkit.util.on(document, 'yooessentials-form:field-change', function (e, ctx) {
                var form = ctx.form;
                var data = ctx.data;
                var field = ctx.field;

                if (form.dataset.yooessentialsFormid === '%s') {
                    %s
                }
            });",
            $node->id,
            $scripts['onfieldchange']
        ),
        [
            'defer' => true,
            'data-preview' => 'diff'
        ]
    );
}
?>

<?= $form($props, array_filter($attrs)) ?>
    <?= $builder->render($children) ?>
    <input type="hidden" name="formid" value="<?= $node->id ?>" />
    <?= $node->form->csrf ?>
    <?= $errors() ?>
<?= $form->end() ?>
