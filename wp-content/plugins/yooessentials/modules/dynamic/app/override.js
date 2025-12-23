/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import hooks from '@yootheme/hooks';
import BaseFields from '@yootheme/fields';

import SourceQuery from './fields/SourceQuery.vue';
import SourceQueryArgs from './fields/SourceQueryArgs';
import SourceQueryField from './fields/SourceQueryField.vue';
import SourceQueryFieldArgs from './fields/SourceQueryFieldArgs';
import SourceQueryFieldDirective from './fields/SourceQueryFieldDirective';
import SourceQueryFieldDirectives from './fields/SourceQueryFieldDirectives';

// override source core fields
hooks.after('app.init', () => {
    BaseFields.components.FieldSourceSelect = SourceQuery;
    BaseFields.components.FieldSourceQueryArgs = SourceQueryArgs;
    BaseFields.components.FieldSourceFieldSelect = SourceQueryField;
    BaseFields.components.FieldSourceFieldArgs = SourceQueryFieldArgs;
    BaseFields.components.FieldSourceFieldDirective = SourceQueryFieldDirective;
    BaseFields.components.FieldSourceFieldDirectives = SourceQueryFieldDirectives;
});
