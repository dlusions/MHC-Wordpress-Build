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
/** @var $children */
/** @var $props */
/** @var $attrs */

// Generate unique IDs
$props['id'] = "js-{$this->uid()}";
$props['connect'] = "js-{$this->uid()}";
$props['item_nav'] = "js-{$this->uid()}";

// Fallbacks
$props['switcher_autoplay_interval'] = (is_numeric($props['switcher_autoplay_interval'] ?? null))
    ? max(2, min(60, $props['switcher_autoplay_interval']))
    : 7;
$props['switcher_scroll_offset'] = (is_numeric($props['switcher_scroll_offset'] ?? null))
    ? max(50, min(300, $props['switcher_scroll_offset']))
    : 100;

// Resets (order matters)
$props['switcher_autoplay'] = count($children) === 1 ? false : $props['switcher_autoplay'];
$props['switcher_thumbnav_shrink'] = $props['switcher_thumbnail_label'] ? false : $props['switcher_thumbnav_shrink'];
$props['switcher_nowrap'] = $props['switcher_thumbnav_shrink'] ? false : $props['switcher_nowrap'];
$props['switcher_accordion_collapsible'] = ($props['switcher_style'] === 'accordion' && $props['switcher_autoplay']) ?
    false : $props['switcher_accordion_collapsible'];

// Switcher
$el = $this->el('div', [
    'class' => [
        'fs-switcher',

        // YOOtheme Pro 5 Margins
        ...(($props['_yootheme_v4'] ?? false) === true ? [
            'uk-margin-top {@margin_top: default}',
            'uk-margin-{!margin_top: |default}-top',
            'uk-margin-bottom {@margin_bottom: default}',
            'uk-margin-{!margin_bottom: |default}-bottom'
        ] : []),
    ],
    'data-style' => ['{switcher_style}'],
    'data-mode' => ['[hover {@switcher_mode}][click {@!switcher_mode}]{@!switcher_style: accordion}'],
    'data-click' => ['1 {@switcher_mode_hover_trigger_click}{@switcher_mode}{@!switcher_style: accordion}'],
    'data-scroll' => ['true {@switcher_scroll}'],
    'data-scroll-offset' => ['{switcher_scroll_offset}'],
    'data-autoplay' => $this->expr([
        'autoplay: {switcher_autoplay}; [pauseOnHover: 1; {@switcher_autoplay_pause}] [autoplayInterval: {switcher_autoplay_interval}000;] [autoplayProgress: 1; {@switcher_autoplay_progress}]',
    ], $props) ?: false,
    'data-accordion' => ['multiple {@switcher_accordion_multiple}{@switcher_style: accordion}{@!switcher_autoplay}'],
    'data-fs-switcher' => 'pro', // JS hook (used in fs-switcher.js)
]);

// Autoplay Progress
$progress = $props['switcher_autoplay'] && $props['switcher_autoplay_progress'] ? include __DIR__ . '/template-progress.php' : '';
?>

<?= $el($props, $attrs) ?>
<?php include __DIR__ . ($props['switcher_style'] === 'accordion' ? '/template-accordion.php' : '/template-switcher.php'); ?>
<?= $el->end() ?>