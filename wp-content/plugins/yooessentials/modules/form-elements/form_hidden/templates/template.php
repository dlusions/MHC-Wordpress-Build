<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$id = $node->control->id ?? null;

if ($node->control->props['id_inherit'] ?? false) {
    $id = $node->control->name;
}

$input = $this->el('input', [
    'type' => 'hidden',
    'id' => $id,
    'name' => $node->control->name,
    'value' => $node->control->value,
]);

$this['ye-form']->customAttributes($input, $node->control->props['attrs'] ?? '');
?>

<?= $input($node->control->props) ?>
