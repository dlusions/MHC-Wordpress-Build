/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import UIkit from 'uikit';
import BaseFields from '@yootheme/fields';
import * as fields from './fields';

Object.assign(BaseFields.components, fields);

// temporal workaround
UIkit.icon.add({
    'ye--condition-report-check':
        '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 16 22"><path fill="none" stroke="#444" d="M5.475 1.966H3.5V18.5h12l.009-16.534h-1.978" transform="matrix(1 0 0 .93688 -1.502 1.72)"/><path fill="none" stroke="#444" d="M4.495 3.562v-1.5h7v1.5m-7 0v1.5h7v-1.5" transform="matrix(1.01271 0 0 1 -.117 0)"/><path fill="none" stroke="#444" class="uk-text-success" stroke-width="1" d="M11.969 8.345 7 16.047l-2.974-3.003"/></svg>',
    'ye--condition-report-ban':
        '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 16 22"><path fill="none" stroke="#444" d="M5.475 1.966H3.5V18.5h12l.009-16.534h-1.978" transform="matrix(1 0 0 .93688 -1.502 1.72)"/><path fill="none" stroke="#444" d="M4.495 3.562v-1.5h7v1.5m-7 0v1.5h7v-1.5" transform="matrix(1.01271 0 0 1 -.117 0)"/><path fill="none" stroke="#444" class="uk-text-danger" d="m5.314 9.618 5.372 5.817M8 8.498c2.209 0 4.027 1.819 4.028 4.028-.001 2.209-1.82 4.027-4.028 4.028-2.21-.001-4.027-1.82-4.028-4.028 0-2.21 1.819-4.027 4.028-4.028Z"/></svg>',
});
