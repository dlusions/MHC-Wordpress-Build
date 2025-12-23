<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

if ($args['show_link'] && $args['link_style']) {
    echo '<span class="uk-' . $args['link_style'] . '">';
}

echo implode(
    $args['separator'],
    array_map(function ($item) use ($args) {
        if (empty($args['show_link'])) {
            return $item['name'];
        }

        return "<a href=\"{$item['link']}\">{$item['name']}</a>";
    }, $items)
);

if ($args['show_link'] && $args['link_style']) {
    echo '</span>';
}
