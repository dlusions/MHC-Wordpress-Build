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

/** @var $builder */
/** @var $children */
/** @var $element */
/** @var $props */
/** @var $__dir */

if (empty($element['show_sublayout']) || !$children) {
    return;
}

// Sublayout
$sublayout = $this->el('div', [
    'class' => [
        'fs-switcher__item-sublayout',
        'uk-margin[-{sublayout_margin}]-top {@!sublayout_margin: remove} {@sublayout_mode: native|mixed}',
        'uk-margin-remove-top {@sublayout_margin: remove} {@sublayout_mode: native|mixed}',
        'uk-margin-remove-bottom {@sublayout_mode: native|mixed}',

        // Deprecated remove in v.2.0.0
        'el-sublayout',
    ],
]);

// Initialize
$renderAsModal = [];
$renderAsNative = [];

// Handle mixed mode
if ($element['sublayout_mode'] === 'mixed') {
    if (!empty($element['sublayout_modal_group_custom'])) {
        $max = count($children);
        $indices = array_map('intval', str_getcsv(trim($element['sublayout_modal_group_custom'])));

        // Convert to 0-based and filter valid indices
        $validIndices = array_filter(
            array_map(static fn($x) => $x - 1, $indices),
            static fn($x) => $x >= 0 && $x < $max
        );

        if ($validIndices) {
            $renderAsModal = array_values($validIndices);
            $renderAsNative = array_diff(array_keys($children), $renderAsModal);
        } else {
            // If no valid indices, all children are sublayouts
            $renderAsNative = array_keys($children);
        }
    } else {
        // If custom wrap is empty, all children are sublayouts
        $renderAsNative = array_keys($children);
    }
}

// Render Sublayout
switch ($element['sublayout_mode']) {
    case 'native':
        echo $sublayout($element, $builder->render($children));
        break;
    case 'modal':
        if ($element['sublayout_modal_group'] === 'each') {
            foreach ($children as $i => $child) {
                echo $this->render("$__dir/template-sublayout-modal", compact('props', 'i'));
            }
        } else {
            echo $this->render("$__dir/template-sublayout-modal", ['props' => $props, 'i' => '0']);
        }
        break;
    case 'mixed':
        foreach ($renderAsModal as $i) {
            echo $this->render("$__dir/template-sublayout-modal", compact('props', 'i'));
        }
        foreach ($renderAsNative as $i) {
            echo $sublayout($element, $builder->render($children[$i]));
        }
        break;
}