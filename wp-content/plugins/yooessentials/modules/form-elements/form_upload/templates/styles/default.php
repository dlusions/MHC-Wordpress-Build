<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$input = $this['ye-form']->input([
    'type' => 'text',
    'disabled' => 'disabled',
    'placeholder' => $node->control->props['placeholder'] ?? null,
]); ?>

<div uk-form-custom="target: true" class="uk-width-1-1">

    <?= $this->render("{$__dir}/_input", ['node' => $node, 'props' => $props]) ?>

    <?= $input([
        'width' => $props['control_width'] ?? '' ?: 'medium',
        'size' => $props['control_size'] ?? '',
    ]) ?>

    <?= $this->render("{$__dir}/_button", ['node' => $node, 'props' => $props]) ?>
</div>
