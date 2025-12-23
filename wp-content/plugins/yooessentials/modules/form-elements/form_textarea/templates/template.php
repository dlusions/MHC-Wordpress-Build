<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$el = $this->el('div');

$id = $node->control->id ?? null;

if ($node->control->props['id_inherit'] ?? false) {
    $id = $node->control->name;
}

$control = $this['ye-form']->control(
    $node->control->name,
    $node->control->props['label'],
    $node->control->props['required'],
    $id
);

$textarea = $this['ye-form']->textarea(
    [
        'id' => $id,
        'name' => $node->control->name,
        'required' => (bool) $node->control->props['required'] ?? null,
        'readonly' => (bool) $node->control->props['readonly'] ?? null,
        'autofocus' => (bool) $node->control->props['autofocus'] ?? null,
        'pattern' => $node->control->props['pattern'] ?? null,
        'minlength' => $node->control->props['minlength'] ?? null,
        'maxlength' => $node->control->props['maxlength'] ?? null,
        'placeholder' => $node->control->props['placeholder'] ?? null,
        'rows' => $node->control->props['rows'] ?? null,
    ],
    $node->control->value
);

$this['ye-form']->customAttributes($textarea, $node->control->props['attrs'] ?? '');
?>

<?= $el($props, $attrs) ?>

    <?= $control() ?>

        <?= $textarea($node->control->props, [
            'state' => $node->control->errors ? 'danger' : null,
        ]) ?>

    <?= $control->end() ?>

<?= $el->end() ?>
