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
/** @var $props */

// Sanitize progress height, default to 1. Ensure it's a valid number.
$props['switcher_autoplay_progress_height'] = !empty($props['switcher_autoplay_progress_height']) && is_numeric($props['switcher_autoplay_progress_height']) ?
    max(0.5, min(10, (int)$props['switcher_autoplay_progress_height'])) : 1;

// Progress Container
$progress_container = $this->el('div', [
    'class' => [
        'fs-switcher__progress',
        'uk-margin[-{switcher_autoplay_progress_margin}]-top {@switcher_autoplay_progress_position: nav-bottom|item-bottom}',
        'uk-margin[-{switcher_autoplay_progress_margin}]-bottom {@switcher_autoplay_progress_position: nav-top|item-top}',
    ],
    'style' => $this->expr([
        '--progress-track: {switcher_autoplay_progress_track};',
        '--progress-height: {switcher_autoplay_progress_height}px;',
    ], $props) ?: false,
]);

// Progress Bar
$progress_bar = $this->el('div', [
    'class' => ['fs-switcher__progress-bar'],
    'style' => $this->expr(['--progress-fill: {switcher_autoplay_progress_fill}'], $props) ?: false,
    'data-fs-switcher-progress-bar', // JS hook (used in fs-switcher.js)
]);

return $progress_container($props, $progress_bar($props, ''));