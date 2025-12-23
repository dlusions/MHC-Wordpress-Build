<?php /**
 * @package     [FS] Switcher SL for YOOtheme Pro
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
/** @var $builder */
/** @var $children */
/** @var $element */
/** @var $props */

// Sublayout
$sublayout = $this->el($props['item_element'] ?: 'div',
    ['class' => ['fs-switcher__item-sublayout', 'el-sublayout', 'uk-margin-remove-bottom']]);
?>

<?= $sublayout($element, $builder->render($children)) ?>