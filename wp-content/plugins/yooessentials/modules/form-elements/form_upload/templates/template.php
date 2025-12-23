<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$el = $this->el('div');

$control = $this['ye-form']->control(
    $node->control->name,
    $node->control->props['label'],
    $node->control->props['required']
);

$layout = $node->props['layout'] ?? '';
?>

<?= $el($props, $attrs) ?>

    <?= $control() ?>
        <?= $this->render("{$__dir}/styles/{$layout}", ['node' => $node, 'props' => $props]) ?>
    <?= $control->end() ?>

<?= $el->end() ?>
