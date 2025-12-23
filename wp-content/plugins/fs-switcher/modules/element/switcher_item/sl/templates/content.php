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
/** @var $props */

?>

<?php if (count($children) > 1) : ?>
    <ul>
        <?php foreach ($children as $child) : ?>
            <li>
                <?= $builder->render($child, ['element' => $props]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php elseif (count($children) === 1) : ?>
    <div>
        <?= $builder->render($children[0], ['element' => $props]) ?>
    </div>
<?php endif ?>