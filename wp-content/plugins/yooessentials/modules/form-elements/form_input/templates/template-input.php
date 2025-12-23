<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$props = array_merge(
    $node->propsControl,
    array_filter($child->control->props, fn ($v) => $v !== null && $v !== '')
);

$showLabel = $node->props['show_label'] ?? false;
$showIcon = $node->props['show_icon'] ?? false;

if ($element['fullwidth'] ?? false) {
    $props['width'] = '';
}

$id = $child->control->id ?? null;

if ($child->control->props['id_inherit'] ?? false) {
    $id = $child->control->name;
}

$control = $this['ye-form']->control(
    $child->control->name,
    $showLabel ? $props['label'] ?? null : null,
    $props['required'] ?? null,
    $id
);

$icon = $this['ye-form']->inputIcon();

$input = $this['ye-form']->input([
    'id' => $id,
    'type' => str_replace('yooessentials_form_input_', '', $child->type),
    'name' => $child->control->name,
    'value' => $child->control->value,
    'required' => (bool) ($props['required'] ?? null),
    'readonly' => (bool) ($props['readonly'] ?? null),
    'autofocus' => (bool) ($props['autofocus'] ?? null),
    'pattern' => $props['pattern'] ?? null,
    'min' => $props['min'] ?? $props['minmonth'] ?? $props['minweek'] ?? $props['mindate'] ?? $props['mintime'] ?? null,
    'max' => $props['max'] ?? $props['maxmonth'] ?? $props['maxweek'] ?? $props['maxdate'] ?? $props['maxtime'] ?? null,
    'minlength' => $props['minlength'] ?? null,
    'maxlength' => $props['maxlength'] ?? null,
    'placeholder' => $props['placeholder'] ?? null,
]);

// disable email cloaking (joomla) if type is email and has a value
if ($input->attrs['type'] === 'email' && $input->attrs['value']) {
    $input->attrs['data-emailcloak'] = '{emailcloak=off}';
}

$this['ye-form']->customAttributes($input, $child->control->props['attrs'] ?? '');

$input = $input->render($props, [
    'state' => $child->control->errors ? 'danger' : null,
]);
?>

<?= $control() ?>

    <?php if ($showIcon && $props['icon']): ?>

        <div class="uk-inline uk-display-block">

            <?= $icon([
                'icon' => $props['icon'],
                'align' => $props['icon_align'],
            ]) ?>

            <?= $input ?>

        </div>

    <?php else: ?>

        <?= $input ?>

    <?php endif; ?>

<?= $control->end() ?>
