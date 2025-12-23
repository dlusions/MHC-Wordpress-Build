<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use function YOOtheme\App;
use YOOtheme\Metadata;

/** @var Metadata $metadata */
$metadata = app(Metadata::class);

$id = uniqid('cf-turnstile_');
$callback = 'yooessentialsTurnstileOnload';

$metadata->set('script:yooessentials-turnstile-callback', [
    'src' => '~yooessentials_url/modules/form-elements/form_turnstile/assets/turnstile.js',
    'async' => true,
    'defer' => true,
]);

$metadata->set('script:yooessentials-turnstile', [
    'src' => "https://challenges.cloudflare.com/turnstile/v0/api.js?onload={$callback}&render=explicit",
    'async' => true,
    'defer' => true,
]);

$sitekey = $node->control->sitekey;
$size = $node->control->props['size'] ?? 'normal';
$theme = $node->control->props['theme'] ?? 'light';
$appearance = $node->control->props['appearance'] ?? 'always';
$compliance = $node->control->props['compliance'] ?? '';

$el = $this->el('div');

$control = $this['ye-form']->controlFieldset($node->control->name, $node->control->props['label'] ?? '', true);
?>

<?= $el($props, $attrs) ?>
<?= $control() ?>

    <?php if ($appearance === 'interaction-only'): ?>
        <?= $compliance ?:
            'This site is protected by Cloudflare Turnstile, its <a href="https://www.cloudflare.com/privacypolicy" target="_blank">Privacy Policy</a> and <a href="https://www.cloudflare.com/website-terms" target="_blank">Terms of Service</a> apply.' ?>
    <?php endif; ?>

    <div id="<?= $id ?>" class="cf-turnstile" data-theme="<?= $theme ?>" data-size="<?= $size ?>" data-sitekey="<?= $sitekey ?>" data-appearance="<?= $appearance ?>"></div>

<?= $control->end() ?>
<?= $el->end() ?>
