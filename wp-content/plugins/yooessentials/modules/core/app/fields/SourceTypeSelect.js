/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import api from '@yootheme/api';
import fields from '@yootheme/fields';

export default {
    name: 'SourceTypeSelect',

    extends: fields.components.FieldSelect,

    methods: {
        filterOptions() {
            const types = api.customizer?.schema?.types;

            return [
                { text: this.$t(this.field?.placeholder || 'None'), value: '' },
                ...types
                    .filter(
                        (type) =>
                            type.kind === 'OBJECT' &&
                            type.name !== 'Query' &&
                            !type.name.startsWith('__')
                    )
                    .map((type) => ({
                        value: type.name,
                        text: type.name
                    }))
            ];
        }
    }
};
