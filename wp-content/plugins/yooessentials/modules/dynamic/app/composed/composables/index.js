/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import state from './state';
import input from './input';
import widget from './widget';
import insert from './insert';
import effects from './effects';
import dragdrop from './dragdrop';
import SourceType from './types/Source/SourceType';
import ConditionType from './types/Condition/ConditionType';

// effects must go first so it can catch tr effects of others
export default [effects, state, input, insert, dragdrop, SourceType, ConditionType, widget];
