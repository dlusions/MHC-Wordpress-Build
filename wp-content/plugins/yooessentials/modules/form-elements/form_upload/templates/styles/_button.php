<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$button = $this->el('a', [
    'tabindex' => '-1',

    'class' => $this->expr(
        [
            'uk-text-nowrap',
            'uk-width-1-1 {@fullwidth}',
            'uk-{style: link-\w+}' => ['style' => $node->button['style']],
            'uk-button uk-button-{!style: |link-\w+} [uk-button-{size}]' => [
                'style' => $node->button['style'],
            ],
        ],
        $node->button
    ),
]); ?>

<?= $button() ?>

<?php if ($node->button['icon']): ?>

    <?php if ($node->button['icon_align'] === 'left'): ?>
        <span uk-icon="<?= $node->button['icon'] ?>"></span>
    <?php endif; ?>

    <span class="uk-text-middle"><?= $node->props['content'] ?></span>

    <?php if ($node->button['icon_align'] === 'right'): ?>
        <span uk-icon="<?= $node->button['icon'] ?>"></span>
    <?php endif; ?>

<?php else: ?>
    <?= $node->props['content'] ?>
<?php endif; ?>

<?= $button->end() ?>
