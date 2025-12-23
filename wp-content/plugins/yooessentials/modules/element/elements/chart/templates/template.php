<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$el = $this->el('div', [
    'data-width' => $node->props['chart_width'] ?? null,
    'data-height' => $node->props['chart_height'] ?? null,
    'uk-yooessentials-chart' => base64_encode(json_encode($node->config)),
]);

?>

<?= $el($props, $attrs) ?><?= $el->end() ?>

<?php if ($node->debug) : ?>
<pre class="uk-hidden">
    <?= json_encode($node->config, JSON_PRETTY_PRINT); ?>
</pre>
<?php endif; ?>
