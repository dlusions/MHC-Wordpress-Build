<div id="<?= $id ?>"></div>

<script type="text/javascript">
(function() {

    const recaptchaEl = UIkit.util.$('#<?= $id ?>');
    const formEl = recaptchaEl.closest('form');

    window.yooessentialsRecaptcha2FormEl = null;

    UIkit.util.on(formEl, 'form:submit', function(e) {
        e.preventDefault();
        window.yooessentialsRecaptcha2FormEl = formEl;
        grecaptcha.execute(recaptchaEl)
    });

    UIkit.util.on(formEl, 'form:submission-error', function() {
        grecaptcha.reset(recaptchaEl);
    });

    UIkit.util.on(formEl, 'reset', function() {
        grecaptcha.reset(recaptchaEl);
    });

    if (!window.yooessentialsRecaptcha2Callback) {
        window.yooessentialsRecaptcha2Callback = function () {
            grecaptcha.render(<?= $id ?>, {
                size: 'invisible',
                theme: '<?= $theme ?>',
                badge: '<?= $badge ?>',
                sitekey: '<?= $node->control->siteKey ?>',
                callback: function(token) {
                    var form = UIkit.yooessentialsForm(window.yooessentialsRecaptcha2FormEl);

                    form.doSubmit({
                        'g-recaptcha-response': token
                    });

                    grecaptcha.reset(recaptchaEl);
                }
            });
        };
    }

})();
</script>
