<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use ZOOlanders\YOOessentials\Util\Prop;

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
            'icon: {0};' => $props['icon'] ?: 'mail',
            'width: {icon_width};',
            'height: {icon_width};',
        ],

    ]);

}

$mailto = $node->props['mailto'] ?? '';
$options = array_filter(Prop::filterByPrefix($node->props, 'email_'));

$query = http_build_query(array_filter($options), '', '&', PHP_QUERY_RFC3986);

// support line breaks, replaces encoded '\n' with %0A
$query = str_replace('%5Cn', '%0A', $query);

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

    'href' => "mailto:$mailto?$query",
    'title' => $props['link_title'],
    'aria-label' => $props['link_aria_label'] ?: $element['link_aria_label'],
    'rel' => 'noreferrer',

]);

?>

<?= $link($element, $icon($element, '')) ?>
