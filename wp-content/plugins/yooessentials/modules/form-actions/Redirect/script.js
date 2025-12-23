/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export default ({ util: { on } }) => {
    on(document, 'yooessentials-form:submitted', (e, { response }) => {
        const redirect = response?.redirect || {};

        if (redirect.to) {
            const timeout = redirect.timeout || 0;

            setTimeout(() => {
                window.open(redirect.to, redirect.blank ? '_blank' : '_self');
            }, timeout * 1000);
        }
    });
};
