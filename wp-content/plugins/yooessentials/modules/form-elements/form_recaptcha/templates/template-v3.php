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

$id = uniqid('reCAPTCHA_');
$action = $node->control->props['action'];

$metadata->set('script:yooessentials-recaptcha', [
    'src' => "https://www.google.com/recaptcha/api.js?render={$node->control->siteKey}",
    'defer' => true,
]);
?>

<input type="hidden" id="<?= $id ?>"/>

<script type="text/javascript">
(function () {

    const recaptchaEl = UIkit.util.$('#<?= $id ?>');
    const formEl = recaptchaEl.closest('form');

    UIkit.util.on(formEl, 'form:submit', function(e) {
        e.preventDefault();

        const form = UIkit.yooessentialsForm(formEl);

        grecaptcha.ready(function() {
            grecaptcha.execute('<?= $node->control->siteKey ?>', {action: '<?= $action ?>'}).then(function(token) {
                form.doSubmit({
                    'g-recaptcha-response': token
                });
            });
        });
    });

})();
</script>
