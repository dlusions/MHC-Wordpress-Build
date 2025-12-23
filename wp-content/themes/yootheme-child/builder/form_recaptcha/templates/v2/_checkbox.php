<div id="<?= $id ?>"></div>

<script type="text/javascript">
(function() {

    window.yooessentialsRecaptchas = window.yooessentialsRecaptchas || [];

    window.yooessentialsRecaptcha2Callback = window.yooessentialsRecaptcha2Callback || function () {
        window.yooessentialsRecaptchas.forEach(captcha => {
            grecaptcha.render(captcha.id, captcha);
        })
    }

    window.yooessentialsRecaptchas.push({
        id: '<?= $id ?>',
        size: '<?= $size ?>',
        theme: '<?= $theme ?>',
        sitekey: '<?= $node->control->siteKey ?>'
    });

    const recaptchaEl = UIkit.util.$('#<?= $id ?>');
    const formEl = recaptchaEl.closest('form');

    UIkit.util.on(formEl, 'form:submission-error', function() {
        grecaptcha.reset(recaptchaEl);
    });

    UIkit.util.on(formEl, 'reset', function() {
        grecaptcha.reset(recaptchaEl);
    });

})();
</script>
