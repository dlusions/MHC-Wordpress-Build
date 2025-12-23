/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

/* global turnstile UIkit */

window.yooessentialsTurnstileOnload = function () {
    const forms = UIkit.util.$$('[data-uk-yooessentials-form]');

    for (const k in forms) {
        const form = forms[k];
        const widget = UIkit.util.$('.cf-turnstile', form);

        if (widget) {
            // https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configurations
            const widgetId = turnstile.render(widget);

            if (!widgetId) {
                throw new Error('Turnstile widget render failed.');
            }

            UIkit.util.on(form, 'form:submit', function (e) {
                e.preventDefault();

                if (turnstile.getResponse(widgetId)) {
                    UIkit.yooessentialsForm(form).doSubmit();
                    return;
                }

                turnstile.execute(widgetId, {
                    callback: function () {
                        UIkit.yooessentialsForm(form).doSubmit();
                    },
                });
            });

            UIkit.util.on(form, 'form:submission-error', function () {
                turnstile.reset(widgetId);
            });

            UIkit.util.on(form, 'reset', function () {
                turnstile.reset(widgetId);
            });
        }
    }
};
