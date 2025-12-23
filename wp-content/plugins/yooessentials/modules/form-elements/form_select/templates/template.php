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

$select = $this['ye-form']->select([
    'id' => $id,
    'name' => $node->control->props['multiple'] ? $node->control->name . '[]' : $node->control->name,
    'required' => (bool) $node->control->props['required'] ?? null,
    'multiple' => (bool) $node->control->props['multiple'] ?? null,
    'autofocus' => (bool) $node->control->props['autofocus'] ?? null,
    'size' => $node->control->props['height'] ?? null,
]);

$this['ye-form']->customAttributes($select, $node->control->props['attrs'] ?? '');
?>

<?= $el($props, $attrs) ?>

    <?= $control() ?>

        <?= $select($node->control->props, [
            'state' => $node->control->errors ? 'danger' : null,
        ]) ?>

            <?php foreach ($children as $child): ?>

                <?php
                $text = $child->props['text'] ?? '';
                $value = $child->props['value'] ?? '';
                $disabled = $child->props['disabled'];
                $selected = in_array($value, $node->control->value);

                if (is_string($text)) {
                    $text = strip_tags($text);
                }

                if ($text === '') {
                    $text = $value;
                }

                // force the value attr to be printed
                if ($value === '') {
                    $value = true;
                }

                $option = $this->el('option', ['disabled' => $disabled, 'selected' => $selected, 'value' => $value], $text);
                $option->attr($child->attrs);
                ?>

                <?= $option() ?>

            <?php endforeach; ?>

        <?= $select->end() ?>

    <?= $control->end() ?>

<?= $el->end() ?>
