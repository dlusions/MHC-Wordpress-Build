<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

echo $this->el('input', [
    'type' => 'text',
    'name' => $node->control->name,
    'value' => '',
    'class' => ['uk-hidden'],
]);

echo $this->el('input', [
    'type' => 'text',
    'name' => $node->control->name . '_time',
    'value' => $node->control->time,
    'class' => ['uk-hidden'],
]);
