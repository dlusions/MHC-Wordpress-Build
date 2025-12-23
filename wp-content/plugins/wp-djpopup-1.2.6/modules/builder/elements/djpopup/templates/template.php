<?php
/**
 * @package DJ-SectionsAnywhere
 * @copyright Copyright (C) 2017  DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJContentFilters is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJContentFilters is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJContentFilters. If not, see <http://www.gnu.org/licenses/>.
 *
 */

$id = uniqid('dj-popup-');
$attrs['id'] = $id;
$modalProps = $props['djpopup'];
$modalAttrs = [];
$directionParam = $props['djpopup_direction'];

$align = ' djpopup--' . str_replace(' ', '-', $directionParam);
$props['align'] = $align;
$el = $this->el('div', [
    'class' => [
        'djpopup-popup',
        'djpopup-{align}',
        'uk-padding-remove {@djpopup_padding: remove}',
        'uk-padding-{djpopup_padding} {@!djpopup_padding: remove}',
        'uk-modal-full' => $props['djpopup_fullscreen'],
        'dj-popup' => (!isset($props['djpopup_overlay']) || !$props['djpopup_overlay']),
        'uk-animation-{djpopup_animation}' => ((isset($props['djpopup_animation'])) && $props['djpopup_animation'])
    ],
    'style' => [
        'margin: {djpopup_margin}px; ',
        (isset($props['djpopup_overlay']) && $props['djpopup_overlay']) ? 'background: ' . $props['djpopup_overlay'] : '',


    ],
    'uk-modal' => true
]);

$elDialog = $this->el('div', [
    'class' => [
        'uk-modal-dialog',
        'uk-position-absolute',
        'djpopup__panel',

    ],
    'style' => $props['djpopup_fullscreen'] ? [] : [
        'width: {djpopup_width}%;' => (isset($props['djpopup_width']) && $props['djpopup_width']),
        'height: {djpopup_height}%;' => (isset($props['djpopup_height']) && $props['djpopup_height']),
        'left: {djpopup_position_left}%;' => (isset($props['djpopup_position_left']) && $props['djpopup_position_left']),
        'top: {djpopup_position_top}%;' => (isset($props['djpopup_position_top']) && $props['djpopup_position_top']),
    ]
]);

$modalBody = $this->el('div', [
    'class' => [
        'uk-modal-dialog uk-modal-body',

    ]
]);

if (isset($attrs->modal_title) && $attrs->modal_title) {
    $modalHeadline = $this->el('span', [
        'class' => [
            'uk-' . $attrs->modal_title_style
        ]
    ]);
}
$isLink = false;
if($props['djpopup_cancel_style'] === 'link-text'){
    $isLink = true;
}
$cancelButton = $this->el(($isLink ? 'a' : 'button'), [
    'class' => [
        'uk-button',
        'uk-modal-close',
        'uk-button-{djpopup_cancel_style}',
    ],
]);

$save_link = (isset($props['djpopup_save_link']) && $props['djpopup_save_link']) ? $props['djpopup_save_link'] : null;
$saveButton = $this->el(($save_link ? 'a' : 'button'), [
    'class' => [
        'uk-button',
        'uk-button-{djpopup_save_style}',
    ],
    'href' => $save_link,
    'style' => [
        'margin: 10px;'
    ]
]);

$headingElement = $this->el('span', [
    'class' => [
        'uk-{djpopup_heading_style}',
    ]
]);

$popupBody = $this->el('div', [
    'class' => [
        'uk-modal-body',
        'uk-section-{style}'

    ]
]);
$repeat = $props['djpopup_repeat'];

$displayPopup = false;
if ($repeat === 'first_time_only') {
    $displayPopup = true;
    echo "<script>
        if (document.cookie.includes('display-popup')) {
            // already shown
            document.dispatchEvent(new CustomEvent('djpopup_prevent'));
        } else {
            document.cookie = 'display-popup=true; path=/; max-age=25922000';
        }
    </script>";
}
if($repeat === 'always'){
    $displayPopup = true;
}
if ($repeat === 'after_x' && isset($props['djpopup_repeatA']->afterxmins)) {
    $displayPopup = true;
    $afterTime = explode(':', (string)$props['djpopup_repeatA']->afterxmins);
    $days = isset($afterTime[0]) ? (int)$afterTime[0] * 86400 : 0;
    $hours = isset($afterTime[1]) ? (int)$afterTime[1] * 3600 : 0;
    $minutes = isset($afterTime[2]) ? (int)$afterTime[2] * 60 : 0;
    $totalDelay = $days + $hours + $minutes;

    echo "<script>
        if (document.cookie.includes('display-popup-time-custom')) {
            document.dispatchEvent(new CustomEvent('djpopup_prevent'));
        } else {
            var expires = new Date();
            expires.setTime(expires.getTime() + " . ($totalDelay * 1000) . ");
            document.cookie = 'display-popup-time-custom=true; expires=' + expires.toUTCString() + '; path=/';
        }
    </script>";
}


?>

<?= $el($props, $attrs) ?>
<?= $elDialog($props); ?>
<?php if (isset($props['djpopup_close_icon']) && $props['djpopup_close_icon']) : ?>
    <button class="uk-modal-close-default uk-modal-close" type="button"
            uk-icon="<?php echo $props['djpopup_close_icon']; ?>"></button>
<?php endif; ?>
<?php if (isset($props['djpopup_heading']) && $props['djpopup_heading']) : ?>
    <div class="uk-modal-header">
        <?= $headingElement($props) ?>
        <?php echo $props['djpopup_heading']; ?>
        <?= $headingElement->end() ?>
    </div>
<?php endif; ?>
<?= $popupBody($props) ?>
<?php echo $builder->render($children); ?>
<?= $popupBody->end() ?>


<?php if ((isset($props['djpopup_save_text']) && $props['djpopup_save_text']) || (isset($props['djpopup_cancel_text']) && $props['djpopup_cancel_text'])) : ?>
    <div class="uk-modal-footer uk-text-right">
        <?php if (isset($props['djpopup_cancel_text']) && $props['djpopup_cancel_text']) : ?>
            <?= $cancelButton($props) ?>
            <?php echo $props['djpopup_cancel_text']; ?>
            <?= $cancelButton->end() ?>
        <?php endif; ?>
        <?php if (isset($props['djpopup_save_text']) && $props['djpopup_save_text']) : ?>
            <?= $saveButton($props) ?>
            <?php echo $props['djpopup_save_text']; ?>
            <?= $saveButton->end() ?>
        <?php endif; ?>

    </div>
<?php endif; ?>


<?= $elDialog->end() ?>
<?= $el->end() ?>

    <script>
        console.log(DJPopup);
        DJPopup.init(document.getElementById('<?php echo $attrs['id'] ?>'), {
            triggers: <?php echo json_encode($props['djpopup']->triggers) ?>
        });
    </script>
<?php


?>