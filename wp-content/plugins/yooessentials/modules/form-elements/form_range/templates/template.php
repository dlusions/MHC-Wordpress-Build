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

$control = $this['ye-form']->control($node->control->name, $node->control->props['label'], false, $id);

$input = $this['ye-form']->range([
    'id' => $id,
    'name' => $node->control->name,
    'value' => $node->control->value,
    'min' => $node->control->props['min'] ?? null,
    'max' => $node->control->props['max'] ?? null,
    'step' => $node->control->props['step'] ?? null,
    'autofocus' => (bool) $node->control->props['autofocus'] ?? null,
]);

$this['ye-form']->customAttributes($input, $node->control->props['attrs'] ?? '');
?>

<?= $el($props, $attrs) ?>

    <?= $control() ?>

        <?= $input($node->control->props) ?>

    <?= $control->end() ?>

<?= $el->end() ?>
