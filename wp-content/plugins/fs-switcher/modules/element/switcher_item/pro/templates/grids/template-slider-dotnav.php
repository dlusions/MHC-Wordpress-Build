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

/** @noinspection DuplicatedCode */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */

$nav = $this->el('ul', [
    'class' => [
        'fs-switcher__fieldset-grid-dotnav',
        'uk-slider-nav',
        'uk-{slider_dotnav}',
        'uk-flex-{slider_dotnav_align}',
    ],
    'uk-margin' => true,
]);

$nav_container = $this->el('div', [
    'class' => [
        'uk-margin[-{slider_dotnav_margin}]-top',
        'uk-visible@{slider_dotnav_breakpoint}',
        'uk-{slider_dotnav_color}',
    ],
]);

echo $element['slider_dotnav_color'] ? $nav_container($element, $nav($element, '')) :
    $nav($element, $nav_container->attrs, '');