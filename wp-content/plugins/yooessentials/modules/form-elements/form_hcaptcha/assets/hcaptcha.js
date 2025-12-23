/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

/* global hcaptcha UIkit */

window.yooessentialsHcaptchaOnload = function () {
    const forms = UIkit.util.$$('[data-uk-yooessentials-form]');

    for (const k in forms) {
        const form = forms[k];
        const captcha = UIkit.util.$('.h-captcha', form);

        if (captcha) {
            // https://docs.hcaptcha.com/configuration#hcaptcharendercontainer-params
            const captchaId = hcaptcha.render(captcha.id);

            UIkit.util.on(form, 'form:submit', function (e) {
                e.preventDefault();

                if (hcaptcha.getResponse(captchaId)) {
                    UIkit.yooessentialsForm(form).doSubmit();
                    return;
                }

                hcaptcha.execute(captchaId, { async: true }).then(function () {
                    UIkit.yooessentialsForm(form).doSubmit();
                });
            });

            UIkit.util.on(form, 'form:submission-error', function () {
                hcaptcha.reset(captchaId);
            });

            UIkit.util.on(form, 'reset', function () {
                hcaptcha.reset(captchaId);
            });
        }
    }
};
