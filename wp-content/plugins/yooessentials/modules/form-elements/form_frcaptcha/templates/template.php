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

$id = uniqid('frc-captcha_');
$callback = 'yooessentialsFrCaptchaCallback';

$metadata->set('script:yooessentials-frc-captcha', [
    'src' => 'https://unpkg.com/friendly-challenge@0.9.5/widget.min.js',
    'async' => true,
    'defer' => true,
]);
$metadata->set('script:yooessentials-frc-captcha-callback', [
    'src' => '~yooessentials_url/modules/form-elements/form_frcaptcha/assets/frcaptcha.js',
    'async' => true,
    'defer' => true,
]);

$lang = $node->control->lang;
$siteKey = $node->control->siteKey;
$endpoint = $node->control->endpoint;

$el = $this->el('div');

$control = $this['ye-form']->controlFieldset($node->control->name, $node->control->props['label'] ?? '', true);
?>

<?= $el($props, $attrs) ?>
<?= $control() ?>

    <div id="<?= $id ?>" class="frc-captcha" data-sitekey="<?= $siteKey ?>" data-lang="<?= $lang ?>" data-endpoint="<?= $endpoint ?>" data-start="none"></div>

<?= $control->end() ?>
<?= $el->end() ?>
