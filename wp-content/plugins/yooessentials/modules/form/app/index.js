/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import api from '@yootheme/api';
import hooks from '@yootheme/hooks';
import BaseFields from '@yootheme/fields';
import essentials from 'yooessentials';
import * as fields from './fields';

import './status';
import './dynamic';
import './transform';
import './placeholder';
import * as FormHelper from './helper';

Object.assign(BaseFields.components, fields);

hooks.before('app.init', ({ extend }) => {
    essentials.helpers.Form = FormHelper;

    extend({
        events: {
            yeOpenFormAreaSettings(e, node) {
                this.$trigger('openDynamicPanel', {
                    ...api.customizer.panels['yooessentials-form-settings'],
                    heading: false,
                    props: {
                        node: node,
                        values: node?.props
                    }
                });
            }
        }
    });
});
