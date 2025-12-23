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
<div id="recaptcha-status-message-<?= $id ?>" aria-live="polite" class="uk-hidden-visually" role="status"></div>
<input type="hidden" id="<?= $id ?>"/>

<script type="text/javascript">
(function () {

    const recaptchaEl = UIkit.util.$('#<?= $id ?>');
    const formEl = recaptchaEl.closest('form');
    
    // Select the specific status message for this instance using the unique ID
    const statusMessage = document.getElementById('recaptcha-status-message-<?= $id ?>');

    UIkit.util.on(formEl, 'form:submit', function(e) {
        e.preventDefault();

        const form = UIkit.yooessentialsForm(formEl);
        
        // 1. Announce verification start
        if (statusMessage) {
            statusMessage.textContent = "Security check initiated.";
        }

        grecaptcha.ready(function() {
            grecaptcha.execute('<?= $node->control->siteKey ?>', {action: '<?= $action ?>'}).then(function(token) {
                
                // 2. Announce verification success before submitting
                if (statusMessage) {
                    // Announce success, which screen reader will read before the form submits
                    statusMessage.textContent = "Security check passed."; 
                }
                
                form.doSubmit({
                    'g-recaptcha-response': token
                });
            }).catch(function(error) { 
                // 3. Announce verification failure
                if (statusMessage) {
                    statusMessage.textContent = "Security check failed. Please refresh and try again.";
                }
            });
        });
    });
})();
</script>