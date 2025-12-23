<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$el = $this->el('div', [
    'class' => [
        'uk-text-{text_style}',
        'uk-text-{text_size} {@!text_style}',
        'uk-text-{text_color}',
        'uk-dropcap {@dropcap}',
        'uk-column-{column}[@{column_breakpoint}]',
        'uk-column-divider {@column} {@column_divider}',
    ],
]);

// Column
$breakpoints = ['xl', 'l', 'm', 's', ''];

if ($props['column'] && false !== ($index = array_search($props['column_breakpoint'], $breakpoints))) {
    list(, $columns) = explode('-', $props['column']);

    foreach (array_splice($breakpoints, $index + 1, 4) as $breakpoint) {
        if ($columns < 2) {
            break;
        }

        $el->attr('class', ['uk-column-1-' . --$columns . ($breakpoint ? "@{$breakpoint}" : '')]);
    }
}

echo $el($props, $attrs, $node->content);
