<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$mimetypes = array_filter(explode(',', str_replace(' ', '', $node->control->props['mimetypes'] ?? '')));
$extensions = array_filter(explode(',', str_replace([' ', '.'], '', $node->control->props['extensions'] ?? '')));
$extensions = array_map(fn ($extension) => '.' . $extension, $extensions);

$accept = array_merge($mimetypes, $extensions);

$id = $node->control->id ?? null;

if ($node->control->props['id_inherit'] ?? false) {
    $id = $node->control->name;
}

$input = $this->el('input', [
    'type' => 'file',
    'id' => $id,
    'name' => $node->control->props['multiple'] ? $node->control->name . '[]' : $node->control->name,
    'value' => $node->control->value,
    'accept' => count($accept) ? implode(',', $accept) : null,
    'multiple' => (bool) $node->control->props['multiple'] ?? null,
    'required' => (bool) $node->control->props['required'] ?? null,
]);

$this['ye-form']->customAttributes($input, $node->control->props['attrs'] ?? '');
?>

<?= $input($node->control->props) ?>
