<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// Image
if ($props['image']) {

    $icon = $this->el('image', [
        'src' => $props['image'],
        'alt' => true,
        'loading' => $element['image_loading'] ? false : null,
        'width' => $element['icon_width'] ?: 20,
        'height' => $element['icon_width'] ?: 20,
        'uk-svg' => $element['image_svg_inline'],
        'thumbnail' => true,
    ]);

// Icon
} else {

    $icon = $this->el('span', [

        'uk-icon' => [
            'icon: {0};' => $props['icon'] ?: 'viber',
            'width: {icon_width};',
            'height: {icon_width};',
        ],

    ]);

}

$text = $node->props['shared_text'] ?? '';

// Link
$link = $this->el('a', [

    'class' => array_merge(
        [
            'el-link',
            'uk-icon-link {@!link_style}',
            'uk-icon-button {@link_style: button}',
            'uk-link-{link_style: muted|text|reset}',
        ],
        $attrs['class']
    ),

    'href' => "viber://forward?text=$text",
    'title' => $props['link_title'],
    'aria-label' => $props['link_aria_label'] ?: $element['link_aria_label'],
    'rel' => 'noreferrer',

]);

?>

<?= $link($element, $icon($element, '')) ?>

