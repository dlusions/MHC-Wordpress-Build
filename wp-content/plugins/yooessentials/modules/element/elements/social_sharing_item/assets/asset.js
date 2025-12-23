/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

window.UIkit.util.on('a[data-yooessentials-social-popup]', 'click', function (e) {
    e.preventDefault();

    const props = JSON.parse(this.dataset.yooessentialsSocialPopup);

    window.UIkit.util.assign(props, {
        left: (window.screen.width - props.width) / 2,
        top: (window.screen.height - props.height) / 4,
        resizible: 'yes',
        scrollbars: 'yes',
        menubar: 'no',
        location: 'no',
        directories: 'no',
        status: 'yes',
    });

    window.open(this.href, 'popupWindow', JSON.stringify(props).replace(/"|{|}/g, '').replace(/:/g, '='));
});
