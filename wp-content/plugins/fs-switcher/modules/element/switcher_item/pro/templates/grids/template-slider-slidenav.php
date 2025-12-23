<?php /**
 * @package     [FS] Switcher Pro for YOOtheme Pro
 * @subpackage  fs-switcher
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/switcher
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection NestedTernaryOperatorInspection, DuplicatedCode */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */

$slidenav = $this->el('a', [
    'class' => [
        'uk-slidenav-large {@slider_slidenav_large}',
    ],
    'href' => '#', // WordPress Preview reloads if `href` is empty
]);

$attrs_slidenav_next = [
    'uk-slidenav-next' => true,
    'uk-slider-item' => 'next',
];

$attrs_slidenav_previous = [
    'uk-slidenav-previous' => true,
    'uk-slider-item' => 'previous',
];

$slidenav_container = $this->el('div', [
    'class' => [
        'fs-switcher__fieldset-grid-slidenav',
        'uk-visible@{slider_slidenav_breakpoint}',
        'uk-hidden-hover uk-hidden-touch {@slider_slidenav_hover}',
        'uk-{slider_slidenav_color}',
    ]
]);

$slidenav_container->attr(['class' => ['uk-position-{slider_slidenav_margin}']]);
$attrs_slidenav_container_next = ['class' => ['uk-position-center-right']];
$attrs_slidenav_container_previous = ['class' => ['uk-position-center-left']];

?>

<?= $slidenav_container($element, $attrs_slidenav_container_previous) ?>
<?= $slidenav($element, $attrs_slidenav_previous, '') ?>
<?= $slidenav_container->end() ?>

<?= $slidenav_container($element, $attrs_slidenav_container_next) ?>
<?= $slidenav($element, $attrs_slidenav_next, '') ?>
<?= $slidenav_container->end() ?>