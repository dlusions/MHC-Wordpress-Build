/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import api from '@yootheme/api';
import find from 'lodash-es/find';
import { keyBy } from '@yooessentials/util';

export default {
    get types() {
        return api.builder.schema?.types;
    },

    get rootQueryType() {
        return find(this.types, { name: api.builder.schema?.queryType?.name });
    },

    get rootQueryFields() {
        return api.builder.helpers.Schema.rootQueryFields;
    },

    isList(field) {
        return field.type?.kind === 'LIST';
    },

    findField(path, base) {
        return api.builder.helpers.Schema.findField(path, base);
    },

    /**
     * Differs from Yoo Source Helper in return result
     * as well as will include not found fields
     * @returns [{field, type}]
     */
    resolvePath(path, base = '') {
        const resolved = resolvePath(this.types, this.rootQueryType, join(base, path));
        return base ? resolved.slice(base.split('.').length) : resolved;
    },

    getFields(field) {
        // workaround to get all fields at once
        return [
            ...this.getFieldsByType(field, 'SCALAR|OBJECT'),
            ...this.getFieldsByType(field, 'LIST')
        ];
    },

    getFieldsByType(field, fieldType = 'SCALAR|OBJECT') {
        // core helper doesn't properly filter types, as a workaround we perform a core fetch
        const fields = api.builder.helpers.Schema.getFields(field, fieldType.split('|').at(0));

        // then apply a proper filter
        return fields.filter(({ _field }) => {
            return _field.type.kind.match(new RegExp(fieldType));
        });
    },

    getFieldType(field) {
        return getFieldType(this.types, field);
    },

    getFieldDataType(field) {
        if (!field?.type) {
            return '';
        }

        const { type, metadata } = field;

        if (type.name === 'String' && (metadata?.filters ?? []).includes('date')) {
            return 'Datetime';
        }

        if (type.kind !== 'SCALAR') {
            const fieldType = this.getFieldType(field);
            return fieldType?.metadata?.label || type.ofType?.name;
        }

        return type.name;
    },

    getFieldFiltersFields(schemaField) {
        const filters = [
            ...(schemaField?.metadata?.filters || []),
            'search',
            'replace',
            'before',
            'after'
        ];

        const fields = filters
            .filter((filter) => api.builder?.sources?.filters?.[filter])
            .map((filter) => ({
                name: filter,
                ...api.builder.sources.filters[filter]
            }));

        return keyBy(fields, 'name');
    },

    getImplodeFields() {
        return {
            implode: api.builder?.sources?.yooessentials?.implode
        };
    },

    getFieldArgumentsFields(schemaField) {
        return schemaField?.metadata?.fields ?? schemaField?.metadata?.arguments ?? {};
    },

    getFieldDirectivesFields(schemaField) {
        const directives = schemaField?.metadata?.directives || ['slice'];

        return directives
            .filter((directive) => api.builder?.sources?.directives?.[directive])
            .map((directive) => ({
                name: directive,
                ...api.builder.sources.directives[directive]
            }));
    },

    getFieldDirectivesDefaults(schemaField) {
        const fields = this.getFieldDirectivesFields(schemaField);

        return fields.map((field) => ({
            name: field.name,
            arguments: getFieldDefaults(flattenObjectFields(field.fields))
        }));
    }
};

function flattenObjectFields(fields) {
    return Object.entries(fields).reduce((acc, [name, field]) => {
        acc[name] = field;

        if (field.fields) {
            acc = { ...acc, ...flattenObjectFields(field.fields) };
        }

        return acc;
    }, {});
}

function getFieldDefaults(fields) {
    return Object.entries(fields).reduce((acc, [name, field]) => {
        if ('default' in field) {
            acc[name] = field.default;
        }

        return acc;
    }, {});
}

export function join(...args) {
    return args.flat(Infinity).filter(Boolean).join('.');
}

function getFieldType(types, field) {
    const { name } = field?.type?.ofType || field?.type || {};
    return name ? types.find((type) => type.name === name) : null;
}

export function flattenFields(fields) {
    return fields.reduce((c, field) => {
        if (field.fields) {
            return [...c, ...flattenFields(field.fields)];
        }

        return [...c, field];
    }, []);
}

function resolvePath(types, type, key) {
    const path = [];
    const parts = key.split('.').filter(Boolean);

    for (const name of parts) {
        const field = type?.fields?.find((field) => field.name === name);
        type = getFieldType(types, field);

        path.push({ field: field || name, type });
    }

    return path;
}
