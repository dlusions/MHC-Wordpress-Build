/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import hooks from '@yootheme/hooks';
import essentials from 'yooessentials';
import ConfigModel from './ConfigModel';

hooks.after('app.init', (app) => {
    essentials.yoo.Config = app.Config;
    essentials.helpers.Config = new Vue(ConfigModel);
});
