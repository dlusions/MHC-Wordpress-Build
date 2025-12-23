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

$id = uniqid('h-captcha_');
$callback = 'yooessentialsHcaptchaOnload';

$metadata->set('script:yooessentials-hcaptcha-callback', [
    'src' => '~yooessentials_url/modules/form-elements/form_hcaptcha/assets/hcaptcha.js',
    'defer' => true,
]);
$metadata->set('script:yooessentials-hcaptcha', [
    'src' => "https://js.hcaptcha.com/1/api.js?onload={$callback}&render=explicit&recaptchacompat=off",
    'async' => true,
    'defer' => true,
]);

$type = $node->control->props['type'];
$siteKey = $node->control->siteKey;
$size = $node->control->props['size'];
$theme = $node->control->props['theme'];
$compliance = $node->control->props['compliance'] ?? '';

if ($type === 'invisible') {
    $size = 'invisible';
}

$params = ['id' => $id, 'callback' => $callback, 'theme' => $theme, 'size' => $size, 'siteKey' => $siteKey];

$el = $this->el('div');

$control = $this['ye-form']->controlFieldset($node->control->name, $node->control->props['label'] ?? '', true);
?>

<?= $el($props, $attrs) ?>
<?= $control() ?>

    <?php if ($type === 'invisible'): ?>
        <?= $compliance ?:
            'This site is protected by hCaptcha and its <a href="https://hcaptcha.com/privacy">Privacy Policy</a> and <a href="https://hcaptcha.com/terms">Terms of Service</a> apply.' ?>
    <?php endif; ?>

    <div id="<?= $id ?>" class="h-captcha" data-theme="<?= $theme ?>" data-size="<?= $size ?>" data-sitekey="<?= $siteKey ?>"></div>

<?= $control->end() ?>
<?= $el->end() ?>

