<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$btnStyle = $props['button_style'] ?? 'default';

$button = $this->el('button', [
    'type' => 'submit',

    'disabled' => true,

    'class' => $this->expr(
        [
            'el-content',
            'uk-inline',
            'uk-width-1-1 {@fullwidth}',
            'uk-{button_style: link-\w+}' => ['button_style' => $btnStyle],
            'uk-button uk-button-{!button_style: |link-\w+} [uk-button-{button_size}]' => [
                'button_style' => $btnStyle,
            ],
        ],
        $element
    ),

    'title' => ['{link_title}'],
]);
?>

<?= $button($props) ?>

<span class="ye-form--btn-content">

    <?php if ($props['icon']): ?>

        <?php if ($props['icon_align'] == 'left'): ?>
        <span uk-icon="<?= $props['icon'] ?>"></span>
        <?php endif; ?>

        <span class="uk-text-middle"><?= $props['content'] ?></span>

        <?php if ($props['icon_align'] == 'right'): ?>
        <span uk-icon="<?= $props['icon'] ?>"></span>
        <?php endif; ?>

    <?php else: ?>

        <?= $props['content'] ?>

    <?php endif; ?>

</span>

<span uk-spinner="ratio: 0.5" class="ye-form--btn-spinner uk-hidden uk-position-center"></span>

<?= $button->end() ?>
