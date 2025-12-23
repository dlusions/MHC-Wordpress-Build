/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import hooks from '@yootheme/hooks';
import essentials from 'yooessentials';
import { IconsModel } from './models';
import { CollectionsIcons, CollectionsSection, MyIcons } from './components';

Vue.component('yooessentials-icon-collections', CollectionsSection);

hooks.after('app.init', () => {
    essentials.helpers.Icons = new Vue(IconsModel);
});

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            iconsModalTabs: ({ result = [] }) => {
                if (essentials.customizer?.icon_collections?.myicons) {
                    result.push({
                        name: 'My Icons',
                        component: MyIcons
                    });
                }

                result.push({
                    name: 'Collections',
                    component: CollectionsIcons
                });

                return result;
            }
        }
    });
});
