<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$el = $this->el('div');

if (count($children) > 1) {
    $control = $this['ye-form']->controlFieldset(
        $node->control->name,
        $node->control->props['label'] ?? '',
        $node->control->props['required'] ?? false,
        $node->control->id
    );
} else {
    $control = $this['ye-form']->control(
        $node->control->name,
        $node->control->props['label'] ?? '',
        $node->control->props['required'] ?? false,
        $node->control->id
    );
}

$options = array_map(function ($child) use ($node, $control) {
    $id = $child->props['id'] ?? null;
    $value = $child->props['value'] ?? '';
    $text = $child->props['text'] ?? '';
    $disabled = $child->props['disabled'];
    $syncId = $node->control->props['id_inherit'] ?? false;
    $checked = $value === $node->control->value;

    if ($text === '') {
        $text = $value;
    }

    // force the value attr to be printed
    if ($value === '') {
        $value = true;
    }

    if (empty($id)) {
        $id = $syncId ? $node->control->name : $node->control->id ?? '';
        $id = "{$id}_{$value}";
    }

    $label = $this->el('label', [
        'class' => ['[uk-disabled uk-text-muted {@disabled}]'],
    ]);

    $input = $this->el('input', [
        'id' => $id,
        'type' => 'radio',
        'name' => $node->control->name,
        'required' => $control->required,
        'class' => ['uk-radio'],
    ]);

    $label->attr(['disabled' => $disabled]);
    $input->attr(['disabled' => $disabled, 'checked' => $checked, 'value' => $value]);
    $input->attr($child->attrs);

    return (object) [
        'label' => $label,
        'text' => $text,
        'input' => $input,
    ];
}, $children);
?>

<?= $el($props, $attrs) ?>

    <?= $control() ?>

    <?php if ($node->control->props['layout'] === 'horizontal'): ?>
    <div class="uk-grid-small uk-child-width-auto" uk-grid>
    <?php endif; ?>

    <?php foreach ($options as $option): ?>

        <?= $option->label->attr([
            'class' => 'uk-flex uk-margin-right',
            'for' => $option->input->attrs['id']
        ]) ?>
            <div><?= $option->input ?></div>
            <div class="uk-margin-small-left"><?= html_entity_decode($option->text) ?></div>
        <?= $option->label->end() ?>

    <?php endforeach; ?>

    <?php if ($node->control->props['layout'] === 'horizontal'): ?>
    </div>
    <?php endif; ?>

    <?= $control->end() ?>

<?= $el->end() ?>
