/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import UIkit from 'uikit';
import http from '@yootheme/http';
import hooks from '@yootheme/hooks';
import BaseFields from '@yootheme/fields';
import essentials from 'yooessentials';

import Util, * as util from './util';
import * as panels from './panels';
import * as fields from './fields';
import * as highlight from './highlight';
import * as components from './components';
import * as codemirror from './codemirror';

Util(Vue);

// init namespaces
essentials.yoo = {};
essentials.helpers = {};

essentials.yoo.highlight = highlight;

Object.assign(BaseFields.components, fields);
Object.assign(essentials, { util, components, codemirror });

Vue.component('yooessentials', panels.EssentialsPanel);
Vue.component('yooessentials-about', panels.AboutPanel);
Vue.component('yooessentials-advanced', panels.AdvancedPanel);
Vue.component('EssentialsHint', components.EssentialsHint);

// set initial fake builder
// necesary for global queries
essentials.yoo.Builder = {
    fake: true,
    view: '',
    path: (node) => [node],
    parent: () => null,
    exists: () => false
};

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            openDynamicPanel(e, panel) {
                if (panel.adjacentOf && essentials.yoo?.Builder?.exists(panel.adjacentOf)) {
                    essentials.yoo.Builder.adjacentOf = panel.adjacentOf;
                    delete panel.adjacentOf;
                }

                this.$trigger('openPanel', {
                    component: components.BuilderPanel,
                    ...panel
                });
            },

            openPanel(e) {
                essentials.yoo.Sidebar = e.sidebar;
            },

            closePanel(e, event, panel) {
                if (panel?.props?.node === essentials.yoo.Builder?.adjacentOf) {
                    essentials.yoo.Builder.adjacentOf = null;
                }
            },

            readyPreview(e, { window = {} }) {
                essentials.logs = window.$yooesslogs || {};
            },

            initBuilder(e) {
                essentials.yoo.Builder = e.origin;
            }
        }
    });
});

// trigger event on each yooessentials
// request in order to collect context
hooks.after('app.init', () => {
    http.addons.unshift({
        beforeRequest(wretch) {
            const [url] = wretch._url.split('?');
            const method = wretch._options.method;

            if (!url.startsWith('yooessentials') || method !== 'POST') {
                return wretch;
            }

            const params = JSON.parse(wretch._options.body);

            Vue.events.trigger('yooessentials-prerequest', params);

            wretch._options.body = JSON.stringify(params);

            return wretch;
        }
    });
});

// set custom admin icons
UIkit.icon.add({
    'ye--link-external':
        '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" width="20" height="20" ><polyline fill="none" stroke="#000" points="14.5 10.5 14.5 16.5 3.5 16.5 3.5 5.5 8.5 5.5"></polyline><polyline fill="none" stroke="#000" points="17.5 8.5 17.5 2.5 11.5 2.5"></polyline><line fill="none" stroke="#000" x1="6.5" y1="13.5" x2="17.5" y2="2.5"></line></svg>',
    'ye--check-circle':
        '<svg viewBox="0 0 16 21" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd"><circle cx="9.5" cy="9.5" r="9" fill="none" stroke="#444" stroke-width="1.32" transform="matrix(.80783 0 0 .80783 .326 2.887)"/><path d="m4.365 10.841 2.237 2.797 5.033-6.152" fill="none" stroke="#444" stroke-width="1.0682057"/></svg>',
    'ye--circle-dashed':
        '<svg viewBox="0 0 16 21" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd"><circle cx="9.5" cy="9.5" r="9" fill="none" stroke="#444" stroke-width="1.32" stroke-dasharray="2.64,1.32,0,0" transform="matrix(.80783 0 0 .80783 .326 2.887)"/></svg>',
    'ye--toggle-on':
        '<svg viewBox="0 0 16 21" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd"><path fill="#444" d="M7.933 2.559h2.566v1H7.933zM11.933 2.559h2.566v1h-2.566zM1.501 2.559h2.435v1H1.5z"/><path fill="#444" d="M5.501 2.559h2.435v1H5.5zM1.5 19.05v-2.435h1v2.434zM1.5 15.634V13.2h1v2.434zM1.5 12.218V9.653h1v2.565zM1.5 8.672V6.106h1v2.566zM1.5 5.125V2.559h1v2.566z"/><g><path fill="#444" d="M13.499 19.05v-2.435h1v2.434zM13.499 15.634V13.2h1v2.434zM13.499 12.218V9.653h1v2.565zM13.499 8.672V6.106h1v2.566zM13.499 5.125V2.559h1v2.566z"/></g><g><path fill="#444" d="M7.93 18.05h2.567v1H7.93zM11.93 18.05h2.567v1H11.93zM1.5 18.05h2.433v1H1.5z"/><path fill="#444" d="M5.5 18.05h2.433v1H5.5z"/></g><g><path d="M8 1.559v1" fill="none" stroke="#444"/></g><g><path d="M8 19.025v1" fill="none" stroke="#444"/></g><g><path fill="#444" d="M7.502 2.559h6.995v16.49H7.502z"/></g></svg>',
    'ye--toggle-off':
        '<svg viewBox="0 0 16 21" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd"><path fill="#444" d="M7.933 2.559h2.566v1H7.933zM11.933 2.559h2.566v1h-2.566zM1.501 2.559h2.435v1H1.5z"/><path fill="#444" d="M5.501 2.559h2.435v1H5.5zM1.5 19.05v-2.435h1v2.434zM1.5 15.634V13.2h1v2.434zM1.5 12.218V9.653h1v2.565zM1.5 8.672V6.106h1v2.566zM1.5 5.125V2.559h1v2.566z"/><g><path fill="#444" d="M13.499 19.05v-2.435h1v2.434zM13.499 15.634V13.2h1v2.434zM13.499 12.218V9.653h1v2.565zM13.499 8.672V6.106h1v2.566zM13.499 5.125V2.559h1v2.566z"/></g><g><path fill="#444" d="M7.93 18.05h2.567v1H7.93zM11.93 18.05h2.567v1H11.93zM1.5 18.05h2.433v1H1.5z"/><path fill="#444" d="M5.5 18.05h2.433v1H5.5z"/></g><g><path d="M8 1.559v1" fill="none" stroke="#444"/></g><g><path d="M8 19.025v1" fill="none" stroke="#444"/></g><g><path fill="#444" d="M1.501 2.559h6v1h-6zM1.999 18.05h6v1H2z"/><path fill="#444" d="M1.5 2.597h1v16.452h-1zM7.502 2.335h1v16.452h-1z"/></g></svg>',
    'ye--hint':
        '<svg viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg" xml:space="preserve"><circle cx="11" cy="11" r="11" style="fill:#e5e5e5"/><path d="M8.094 15.362c-.826.319-1.386.416-2.905 1.468l-.014.014s0-.014.014-.014c1.066-1.551 1.186-2.119 1.519-2.963.333-.845 1.293-1.288 1.293-1.288l.746.692.64.803s-.453.984-1.293 1.288Z" style="fill:#898989"/><path d="M6.348 15.667c.267-.499.4-.886.547-1.274.053-.125.093-.263.146-.402.174-.443.813-.983 1.08-1.135l.48.512.493.526c-.173.263-.667.942-1.106 1.108-.134.056-.267.097-.387.139-.386.138-.76.263-1.253.526Z" style="fill:#e5e5e5"/><path d="M7.015 15.002c.093-.194.173-.388.253-.568.067-.138.12-.29.2-.429.051-.107.143-.233.253-.362a5.62 5.62 0 0 1 .587-.58l.586.637c-.28.347-.653.762-.906.873-.147.069-.294.125-.427.18-.173.083-.346.152-.546.249Z" style="fill:#fff"/><path fill="#444" d="M11.56 13.119c.013.194-.014.401-.067.595-.293 1.011-1.333 2.05-1.333 2.05.027.014 1 .595 2.253-.111.666-.374 1.052-.997 1.279-1.523.04-.097.013-.18-.04-.25a.218.218 0 0 0-.16-.069c-.053 0-.107.014-.146.069l.439-1.495.32-1.122c-1.226.554-2.545 1.856-2.545 1.856ZM8.987 10.349c-.186-.028-.386 0-.586.056-.986.263-2.013 1.315-2.013 1.315-.013-.028-.546-1.052.187-2.34.386-.679 1-1.067 1.506-1.274a.177.177 0 0 1 .227.055c.04.042.066.097.066.166a.227.227 0 0 1-.066.153l1.452-.402 1.093-.291c-.56 1.233-1.866 2.562-1.866 2.562Z"/><path fill="#444" d="M13.999 11.374c-.334.332-.6.595-.893.831a6.728 6.728 0 0 1-.987.651c-.013.014-.026.014-.04.014l-.653.304-.719.333-1.373.637-1.426-1.538.08-.166.586-1.246.347-.734a9.117 9.117 0 0 1 1.772-2.534c.027-.028.038-.044.067-.07 1.352-1.193 1.976-1.682 2.892-2.049.822-.33 1.449-.489 1.946-.568.355-.056.653-.076.866-.083.062-.002.373.166.36.402-.027.415-.157 1.189-.386 1.98-.014.028-.054.194-.08.249-.054.139-.12.291-.187.443-.027.056-.053.125-.08.18-.44.942-1.093 1.953-2.092 2.964Z"/><path d="M14.561 9.028a1.127 1.127 0 0 1-1.587-.026A1.127 1.127 0 0 1 13 7.415a1.126 1.126 0 0 1 1.587.026c.43.442.417 1.145-.026 1.587Z" style="fill:#fff"/></svg>'
});
