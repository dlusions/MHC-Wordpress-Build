/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import hooks from '@yootheme/hooks';
import BaseFields from '@yootheme/fields';
import essentials from 'yooessentials';
import { SourcesModel } from './models';
import { SourcesSection } from './components';
import * as fields from './fields';

Vue.component('yooessentials-sources', SourcesSection);

Object.assign(BaseFields.components, fields);

hooks.after('app.init', () => {
    essentials.helpers.Sources = new Vue(SourcesModel);
});
