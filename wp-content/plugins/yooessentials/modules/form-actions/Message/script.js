/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export default ({ util: { on, addClass }, modal }) => {
    on(document, 'yooessentials-form:submitted', (e, { form, data, response }) => {
        if (response?.message_action) {
            try {
                const { content, stack, center, onshown, onhidden } = response.message_action;
                const instance = modal.alert(content, { stack }).dialog;

                addClass(instance.$el, 'ye-form-modal');

                on(instance.$el, 'beforeshow', () => {
                    if (center) {
                        addClass(instance.panel, 'uk-margin-auto-vertical');
                    }
                });

                on(instance.$el, 'shown', (event) => {
                    if (onshown) {
                        new Function('event', 'form', 'data', 'response', onshown).call(
                            instance,
                            event,
                            form,
                            data,
                            response
                        );
                    }
                });

                on(instance.$el, 'hidden', (event) => {
                    if (onhidden) {
                        new Function('event', 'form', 'data', 'response', onhidden).call(
                            instance,
                            event,
                            form,
                            data,
                            response
                        );
                    }
                });
            } catch {
                e;
            }
        }
    });
};
