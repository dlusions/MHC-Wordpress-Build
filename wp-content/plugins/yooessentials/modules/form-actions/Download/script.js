/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export default ({ util: { on } }) => {
    on(document, 'yooessentials-form:submitted', (e, { response }) => {
        if (response?.download) {
            response.download.forEach(downloadFile);
        }
    });
};

function downloadFile(url) {
    var link = document.createElement('a');

    link.href = url;
    link.download = url.substring(url.lastIndexOf('/') + 1);

    link.style.display = 'none';
    document.body.appendChild(link);

    link.click();
    document.body.removeChild(link);
}
