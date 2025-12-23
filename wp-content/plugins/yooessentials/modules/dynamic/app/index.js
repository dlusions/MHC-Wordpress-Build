/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import UIkit from 'uikit';
import api from '@yootheme/api';
import hooks from '@yootheme/hooks';
import BaseFields from '@yootheme/fields';
import essentials from 'yooessentials';
import { DynamicModel } from './models';
import { SourcePanel, GlobalQueriesSection } from './components';
import Source from './Source';
import SourceHelper from './SourceHelper';
import SchemaHelper from './SchemaHelper';
import * as fields from './fields';

import './hooks';
import './status';
import './override';

Vue.component('yooessentials-global-queries', GlobalQueriesSection);

Object.assign(BaseFields.components, fields);

hooks.before('app.init', ({ extend }) => {
    essentials.helpers.Source = SourceHelper;
    essentials.helpers.Schema = SchemaHelper;
    essentials.helpers.Dynamic = new Vue(DynamicModel);
    essentials.helpers.makeSource = (values, node) => new Source(values, node);

    extend({
        events: {
            openSourcePanel(e, panel) {
                this.$trigger('openPanel', {
                    component: SourcePanel,
                    heading: false,
                    ...api.customizer.panels['yooessentials-source'],
                    ...panel
                });
            }
        }
    });
});

// remove Node Dynamic Option for query dynamic conditions
// when the query is being set in the node (to avoid a loop)
Vue.events.on(
    'essentialsDynamicOptionMatchNode',
    ({ result }, options, field) => {
        result = result || options;

        const stack = essentials.yoo?.Sidebar?.stack || [];

        const isQueryArgument = field?.origin?.startsWith('query-args');
        const isQuerySettingsPanel = ({ name }) => name.startsWith('yooessentials-query-settings');
        const isQueryConditionPanel = ({ name }) =>
            name.startsWith('yooessentials-query-conditions');

        if (
            (isQueryArgument || stack.some(isQueryConditionPanel)) &&
            !stack.some(isQuerySettingsPanel)
        ) {
            return result.filter((opt) => !['node'].includes(opt.name));
        }

        return result;
    },
    -100
);

UIkit.icon.add({
    'ye--dynamic':
        '<svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg" width="10" height="11"><path fill="none" stroke="#000" d="M5,4.5c3,0,4.5-.84,4.5-2S8,.5,5,.5.5,1.34.5,2.5,2,4.5,5,4.5Z"></path><path fill="none" stroke="#000" d="M9.5,5C9.5,6.16,8,7.5,5,7.5S.5,6.65.5,5.5"></path><path fill="none" stroke="#000" d="M9.5,2.5v5c0,1.74-.36,3-4.5,3S.5,9.23.5,7.5v-5"></path></svg>',
    'ye--dynamic-n':
        '<svg viewBox="0 0 11 11" xmlns="http://www.w3.org/2000/svg" width="11" height="11"><path fill="none" stroke="#a0a0a0" d="m5,4.5c3,0,4.5-.8,4.5-2S8,.5,5,.5.5,1.3.5,2.5s1.5,2,4.5,2Z" /><path fill="none" stroke="#a0a0a0" d="m.5,5.5c0,1.2,1.5,2,4.5,2h.5" /><path fill="none" stroke="#a0a0a0" d="m.5,2.5v5c0,1.7.4,3,4.5,3h.5" /><path fill="none" stroke="#a0a0a0" d="m9.5,2.5v2" /><polygon fill="#a0a0a0" points="11 6 11 11 10 11 8 7.67 8 11 7 11 7 6 8 6 10 9.33 10 6 11 6" /></svg>',
    'ye--composed-condition':
        '<svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd"><path fill="none" stroke="#444" stroke-width="1.08" d="M5 10.394s4.011-1.92 4.011-5.253V2.837a8.426 8.426 0 0 1-2.005-.539A10.25 10.25 0 0 1 5 .794a10.25 10.25 0 0 1-2.006 1.504 8.426 8.426 0 0 1-2.005.539v2.304C.989 8.474 5 10.394 5 10.394Zm0-6.716v3.268"/></svg>',
    'ye--composed-calculation':
        '<svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" clip-rule="evenodd"><path fill="none" stroke="#444" stroke-width="18.65" d="M224 88c0-22.077-19.341-40-43.165-40H75.165C51.341 48 32 65.923 32 88v80c0 22.077 19.341 40 43.165 40h105.67C204.659 208 224 190.077 224 168V88Z" transform="matrix(0 .05205 -.05617 0 12.189 -1.165)"/><circle cx="104" cy="128" r="16" fill="#444" transform="matrix(.05543 0 0 .05167 -2.103 -1.114)"/><circle cx="152" cy="128" r="16" fill="#444" transform="matrix(.05543 0 0 .05167 -2.103 -1.114)"/><circle cx="104" cy="176" r="16" fill="#444" transform="matrix(.05543 0 0 .05167 -2.103 -1.114)"/><circle cx="152" cy="176" r="16" fill="#444" transform="matrix(.05543 0 0 .05167 -2.103 -1.114)"/><path fill="none" stroke="#444" stroke-width="20.64" d="M92 76h72" transform="matrix(.05543 0 0 .05167 -2.103 -1.114)"/></svg>',
    'ye--composed-fragment':
        '<svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" clip-rule="evenodd"><path fill="none" stroke="#444" stroke-width="18.65" d="M224 88c0-22.077-19.341-40-43.165-40H75.165C51.341 48 32 65.923 32 88v80c0 22.077 19.341 40 43.165 40h105.67C204.659 208 224 190.077 224 168V88Z" transform="matrix(0 .05205 -.05617 0 12.189 -1.165)"/><circle cx="104" cy="128" r="16" fill="#444" transform="matrix(.05543 0 0 .05167 -2.823 -1.114)"/><circle cx="104" cy="128" r="16" fill="#444" transform="matrix(.05543 0 0 .05167 -.765 -1.114)"/><circle cx="104" cy="128" r="16" fill="#444" transform="matrix(.05543 0 0 .05167 1.293 -1.114)"/></svg>',
    'ye--composed-fullscreen':
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="80" height="80"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"></path></svg>',
    'ye--composed-fullscreen-exit':
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="80" height="80"><path d="M10 4H8v4H4v2h6zM8 20h2v-6H4v2h4zm12-6h-6v6h2v-4h4zm0-6h-4V4h-2v6h6z"></path></svg>'
});
