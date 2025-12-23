/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import UIkit from 'uikit';
import Chart from 'chart.js/auto';
import Deferred from 'chartjs-plugin-deferred';
import { addClass, append, attr, css, isUndefined } from 'uikit-util';

UIkit.component('yooessentials-chart', {
    args: 'config',

    beforeConnect() {
        this.config = JSON.parse(atob(this.config));
        attr(this.$el, 'uk-yooessentials-chart', '');
    },

    connected() {
        addClass(this.$el, 'uk-position-relative');
        css(this.$el, { width: this.width, height: this.height });

        const canvas = append(this.$el, '<canvas/>');

        if (this.config?.options?.plugins?.deferred) {
            this.config.plugins = [Deferred];
        }

        this.processTooltipCallback('title');
        this.processTooltipCallback('label');
        this.processTooltipCallback('footer');
        this.processTicksCallback('y');
        this.processTicksCallback('x');

        this.$chart = new Chart(canvas, this.config);
    },

    props: {
        config: String,
        width: String,
        height: String
    },

    data: function () {
        return {
            config: {}
        };
    },

    methods: {
        processTooltipCallback(type) {
            const callback = this.config?.options?.plugins?.tooltip?.callbacks?.[type];
            const debug = this.config.debug;

            if (isUndefined(callback)) {
                return;
            }

            this.config.options.plugins.tooltip.callbacks[type] = function (items) {
                if (!callback) {
                    return;
                }

                try {
                    const item = items[0] || items;

                    // set metadata ref
                    item.metadata = item.dataset?.metadata?.[item.dataIndex];

                    const cb = new Function('items', 'item', `return ${callback}`);

                    return cb.call(this, items, item);
                } catch (e) {
                    if (debug) {
                        console.error(e);
                    }
                    return 'Error';
                }
            };
        },

        processTicksCallback(scale) {
            const callback = this.config?.options?.scales?.[scale]?.ticks?.callback;
            const debug = this.config.debug;

            if (isUndefined(callback)) {
                return;
            }

            if (!callback) {
                delete this.config.options.scales[scale].ticks.callback;
                return;
            }

            this.config.options.scales[scale].ticks.callback = function (value, index, ticks) {
                try {
                    const cb = new Function(
                        'label',
                        'value',
                        'index',
                        'ticks',
                        `return ${callback}`
                    );
                    const label = this.getLabelForValue(value);

                    return cb.call(this, label, value, index, ticks);
                } catch (e) {
                    if (debug) {
                        console.error(e);
                    }

                    return 'Error';
                }
            };
        }
    }
});
