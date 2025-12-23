/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';

export const customOption = {
    priority: -99,
    group: '',
    name: 'custom',
    title: 'Custom',
    match: (source) => !source.query.startsWith('~'),
    matchNode: () => true, // fallback for all not matched
    queries() {
        const { Schema } = api.builder.helpers;

        const CoreGroups = {
            Page: Vue.i18n.t('Page'),
            Parent: Vue.i18n.t('Parent'),
            Global: Vue.i18n.t('Global'),
            Submission: Vue.i18n.t('Submission')
        };

        const hasGroup = (f) => Boolean(f?.metadata?.group);
        const hasCustomGroup = (f) => f?.metadata?.group === Vue.i18n.t('Custom');
        const hasCustomGroupFields = (f) => Schema.getFieldType(f).fields.some(hasCustomGroup);
        const isCoreQuery = (f) => Object.values(CoreGroups).includes(f?.metadata?.group);

        const fields = Schema.rootQueryType.fields.reduce((carry, field) => {
            if (hasGroup(field) && (hasCustomGroup(field) || !isCoreQuery(field))) {
                carry.push(field);
            } else if (hasCustomGroupFields(field)) {
                const fields = Schema.getFieldType(field).fields.filter(hasCustomGroup);
                const label = field?.metadata?.label || fields?.[0]?.metadata?.label;

                carry.push({
                    name: field.name,
                    type: field.type,
                    metadata: {
                        label: label,
                        group: Vue.i18n.t('Custom'),
                        description: Vue.i18n.t('Fetch %label% by custom criteria', {
                            label: label.toLowerCase().replace('custom ', '')
                        })
                    }
                });
            }

            return carry;
        }, []);

        return fields;
    }
};
