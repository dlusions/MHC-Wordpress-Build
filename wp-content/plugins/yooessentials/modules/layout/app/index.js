/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import hooks from '@yootheme/hooks';
import essentials from 'yooessentials';
import { LayoutsModel } from './models';
import { LibrariesSection } from './components';

import './extend';

Vue.component('yooessentials-layout-libraries', LibrariesSection);

hooks.after('app.init', ({ Library }) => {
    essentials.yoo.Library = Library;
    essentials.helpers.Layouts = new Vue(LayoutsModel);
});
