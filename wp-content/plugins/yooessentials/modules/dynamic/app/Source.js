/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';
import { isString, isEmpty } from 'uikit-util';
import { set } from '@yooessentials/util';
import essentials from 'yooessentials';
import SourceHelper from './SourceHelper';
import SchemaHelper, { join } from './SchemaHelper';
import find from 'lodash-es/find';

export default class Source {
    node;
    values;

    constructor(values = {}, node) {
        this.values = values;

        if (node) {
            this.node = SourceHelper.resolveAdjacentNode(node);
        }
    }

    get path() {
        return join(
            api.builder.helpers.Source.getSourcePath(SourceHelper.builderPath(this, this.node)),
            this.field
        );
    }

    get basePath() {
        if (this.field && this.path.endsWith(this.field)) {
            return this.path.slice(0, -`.${this.field}`.length);
        }

        return this.path;
    }

    get queryPath() {
        if (!this.values.query) {
            return null;
        }

        if (this.queryField && this.basePath.endsWith(this.queryField)) {
            return this.basePath.slice(0, -`.${this.queryField}`.length);
        }

        return this.basePath;
    }

    get nodeSource() {
        return new Source(this.node.source, this.node);
    }

    get nodeType() {
        return essentials.yoo.Builder.type(this.node);
    }

    get parentNode() {
        const ancestors = essentials.yoo.Builder?.path(this.node) || [];

        // skip current node
        ancestors.splice(0, 1);

        for (const node of ancestors) {
            const source = new Source(node.source, node);

            if (source.query) {
                return node;
            }
        }

        return null;
    }

    get parentSource() {
        const parent = this.parentNode;
        return parent ? new Source(parent.source, parent) : null;
    }

    /**
     * Is...
     */
    get isEmpty() {
        return isEmpty(this.values);
    }

    get isInheriting() {
        return this.isNodeInheriting || this.isParentInheriting;
    }

    get isNodeInheriting() {
        if (this.values?.query?.name?.startsWith(SourceHelper.nodeKey)) {
            return true;
        }

        // support for core sources inheriting without query set
        return !this.isComposed && !this.values?.query?.name && this.node?.source?.query?.name;
    }

    get isParentInheriting() {
        return this.values?.query?.name === SourceHelper.parentKey;
    }

    get isGlobalQuery() {
        return this.values?.query?.name?.startsWith(SourceHelper.globalKey);
    }

    get isExtending() {
        return this.isInheriting && Boolean(this.values?.query?.field?.name);
    }

    get isComposed() {
        return Boolean(this.values.composed);
    }

    get isFieldValid() {
        return Boolean(this.fieldSchemaType);
    }

    get isQueryValid() {
        return Boolean(this.querySchemaType);
    }

    get isQueryFieldValid() {
        return Boolean(this.queryFieldSchemaType);
    }

    get isMultipleSource() {
        if (this.isInheriting && !this.values?.query?.field) {
            return false;
        }

        return SchemaHelper.findField(this.basePath)?.type?.kind === 'LIST';
    }

    /**
     * Composed
     */
    get composed() {
        return this.values.composed;
    }

    /**
     * Field
     */
    get field() {
        return this.values.name ?? '';
    }

    set field(name) {
        name = name || '';

        // workaround for when the path includes the queryField
        const [queryFieldPath, fieldPath] = splitFieldPath(name, this.basePath);

        if (queryFieldPath) {
            this.queryField = queryFieldPath;
        }

        const fieldSchema = SchemaHelper.findField(join(this.basePath, name));

        if (fieldSchema) {
            applyFieldsDefaults(
                this.values,
                'filters',
                SchemaHelper.getFieldFiltersFields(fieldSchema)
            );

            applyFieldsDefaults(
                this.values,
                'arguments',
                SchemaHelper.getFieldArgumentsFields(fieldSchema)
            );
        }

        Vue.set(this.values, 'name', fieldPath);

        if (this.isMultipleSource) {
            applyFieldsDefaults(this.values, 'implode', SchemaHelper.getImplodeFields());
        } else {
            Vue.delete(this.values, 'implode');
        }
    }

    /**
     * Query
     */
    get query() {
        return this.values.query?.name || '';
    }

    get queryArguments() {
        return this.values.query?.arguments ?? {};
    }

    getQueryArgument(name) {
        return this.values.query?.arguments?.[name];
    }

    getQueryExtendedArgument(name) {
        return this.values.query?.arguments_extended?.[name];
    }

    setQueryArgument(name, value) {
        set(this.values.query, `arguments.${name}`, value);
    }

    setQueryExtendedArgument(name, value) {
        set(this.values.query, `arguments_extended.${name}`, value);
    }

    removeQueryArgument(name) {
        for (const namespace of ['arguments', 'arguments_extended']) {
            if (this.values.query?.[namespace]?.[name]) {
                Vue.delete(this.values.query[namespace], name);

                if (isEmpty(this.values.query[namespace])) {
                    Vue.delete(this.values.query, namespace);
                }
            }
        }
    }

    set query(name) {
        name = name || '';

        if (this.values.query?.name === name) {
            return;
        }

        if (!name) {
            this.queryField = null;
            Vue.delete(this.values, 'query');
            return;
        }

        Vue.set(this.values, 'query', { name });

        const querySchemaField = SchemaHelper.findField(name);

        if (querySchemaField) {
            applyFieldsDefaults(
                this.values.query,
                'arguments',
                SchemaHelper.getFieldArgumentsFields(querySchemaField)
            );
        }

        // workaround, if no query field make sure implode is cleaned
        if (!this.queryField) {
            Vue.delete(this.values, 'implode');
        }
    }

    /**
     * Query Field (multi-item)
     */
    get queryField() {
        return this.values.query?.field?.name ?? '';
    }

    get queryFieldArguments() {
        return this.values.query?.field?.arguments ?? {};
    }

    getQueryFieldArgument(name) {
        return this.values.query?.field?.arguments_extended?.[name];
    }

    getQueryFieldExtendedArgument(name) {
        return this.values.query?.field?.arguments_extended?.[name];
    }

    setQueryFieldArgument(name, value) {
        set(this.values.query, `field.arguments.${name}`, value);
    }

    setQueryFieldExtendedArgument(name, value) {
        set(this.values.query, `field.arguments_extended.${name}`, value);
    }

    removeQueryFieldArgument(name) {
        for (const namespace of ['arguments', 'arguments_extended']) {
            if (this.values.query?.field?.[namespace]?.[name]) {
                Vue.delete(this.values.query.field[namespace], name);

                if (isEmpty(this.values.query.field[namespace])) {
                    Vue.delete(this.values.query.field, namespace);
                }
            }
        }
    }

    getQueryFieldDirective(name) {
        return find(this.values.query?.field?.directives, { name });
    }

    getQueryFieldDirectiveArg(directive, name) {
        return this.getFieldDirectiveArgs(directive)?.[name];
    }

    getQueryFieldDirectiveArgs(directive) {
        return this.getQueryFieldDirective(directive)?.arguments;
    }

    setQueryFieldDirectiveArg(directive, name, value) {
        let dir = this.getQueryFieldDirective(directive);

        if (!dir) {
            dir = { name: directive };

            if (!this.values.query?.field?.directives) {
                set(this.values, 'query.field.directives', []);
            }

            this.values.query.field.directives.push(dir);
        }

        set(dir, `arguments.${name}`, value);
    }

    set queryField(name) {
        name = name || '';

        if (!name) {
            if (this.values.query?.field) {
                Vue.delete(this.values.query, 'field');
            }

            // TODO: perhaps move implode to the field namespace
            Vue.delete(this.values, 'implode');
            return;
        }

        if (!this.values.query) {
            Vue.set(this.values, 'query', {});
        }

        Vue.set(this.values.query, 'field', { name });

        const queryFieldSchema = SchemaHelper.findField(join(this.query, name));

        if (queryFieldSchema) {
            const directiveDefaults = SchemaHelper.getFieldDirectivesDefaults(queryFieldSchema);

            if (!isEmpty(directiveDefaults)) {
                Vue.set(this.values.query.field, 'directives', directiveDefaults);
            }

            applyFieldsDefaults(
                this.values.query.field,
                'arguments',
                SchemaHelper.getFieldArgumentsFields(queryFieldSchema)
            );
        }
    }

    /**
     * Node
     */
    get nodeQuery() {
        return this.node?.source?.query?.name || '';
    }

    get nodeQueryField() {
        return this.node?.source?.query?.field?.name || '';
    }

    /**
     * Schema
     */
    get fieldSchema() {
        return this.field && SchemaHelper.findField(this.path);
    }

    get fieldSchemaType() {
        return SchemaHelper.getFieldDataType(this.fieldSchema);
    }

    get baseSchema() {
        return this.basePath && SchemaHelper.findField(this.basePath);
    }

    get baseSchemaType() {
        return SchemaHelper.getFieldType(this.baseSchema);
    }

    get querySchema() {
        return this.query && SchemaHelper.findField(this.queryPath);
    }

    get querySchemaType() {
        return SchemaHelper.getFieldType(this.querySchema);
    }

    get queryFieldSchema() {
        return this.queryField && SchemaHelper.findField(this.basePath);
    }

    get queryFieldSchemaType() {
        return SchemaHelper.getFieldType(this.queryFieldSchema);
    }

    /**
     * Overview
     */
    get fieldOverview() {
        let overview = getSchemaPathOverview(this.field, this.basePath).join(' / ');

        if (this.fieldSchema?.metadata?.group) {
            overview = `${this.fieldSchema.metadata.group} - ${overview}`;
        }

        if (this.baseSchemaType?.metadata?.label) {
            overview = `${this.baseSchemaType.metadata.label} / ${overview}`;
        }

        if (this.fieldSchemaType?.metadata?.label) {
            overview = `${this.fieldSchemaType.metadata.label} / ${overview}`;
        }

        if (this.isGlobalQuery) {
            const query = SourceHelper.getGlobalQuery(this.values.query);

            overview = Vue.i18n.t('Global / %title% (%label%)', {
                title: query?.name ?? 'Unknown',
                label: `<span class="uk-text-small">${overview.join('/')}</span>`
            });
        }

        return overview;
    }

    get queryOverview() {
        const overview = getSchemaPathOverview(this.queryPath);

        if (this.isInheriting) {
            const title = Vue.i18n.t(this.isParentInheriting ? 'Parent' : 'Node');
            const type = this.isParentInheriting
                ? (this.parentSource?.nodeType?.title ?? 'Unknown')
                : (this.nodeType?.title ?? 'Unknown');
            const label = `<span class="uk-text-small">${overview.join('/')}</span>`;

            return Vue.i18n.t('%title% - %type% (%label%)', { title, type, label });
        }

        if (this.isGlobalQuery) {
            const query = SourceHelper.getGlobalQuery(this.values.query);

            return Vue.i18n.t('Global / %title% (%label%)', {
                title: query?.name ?? 'Unknown',
                label: `<span class="uk-text-small">${overview.join('/')}</span>`
            });
        }

        return overview.join(' / ');
    }

    get queryFieldOverview() {
        return getSchemaPathOverview(this.queryField, this.queryPath).join(' / ');
    }

    get baseOverview() {
        return getSchemaPathOverview(this.basePath).join(' / ');
    }

    get pathOverview() {
        const parts = [];

        if (this.isInheriting) {
            const query = this.baseOverview.replace(/ \/ /g, '/');
            const title = Vue.i18n.t(this.isParentInheriting ? 'Parent' : 'Node');
            const type = this.isParentInheriting
                ? (this.parentSource?.nodeType?.title ?? 'Unknown')
                : (this.nodeType?.title ?? 'Unknown');
            const label = `<span class="uk-text-small">${query}</span>`;

            parts.push(
                Vue.i18n.t('%title% - %type% (%label%)', {
                    title,
                    type,
                    label
                })
            );
        } else {
            parts.push(this.baseOverview);
        }

        return parts.join(' / ');
    }
}

function applyFieldsDefaults(values, namespace, fields) {
    const defaults = getFieldsDefaults(fields);

    if (!values[namespace]) {
        Vue.set(values, namespace, {});
    }

    // apply defaults
    for (const name in defaults) {
        Vue.set(values[namespace], name, defaults[name]);
    }

    const keys = getFieldsKeys(fields);

    // delete unrelated (previous values)
    for (const name in values[namespace]) {
        if (!keys.includes(name)) {
            Vue.delete(values[namespace], name);
        }
    }

    if (isEmpty(values[namespace])) {
        Vue.delete(values, namespace);
    }
}

function getSchemaPathOverview(path, base) {
    const segments = SchemaHelper.resolvePath(path, base);

    if (!segments.length) {
        return [];
    }

    const parts = [];

    if (!base) {
        const { field, type } = segments[0];

        if (field?.metadata?.group) {
            parts.push({
                label: field.metadata.group,
                type
            });
        }
    }

    for (const { field, type } of segments) {
        let label = field?.metadata?.label || (isString(field) ? field : null);

        if (label) {
            const group = field?.metadata?.group;

            if (label.startsWith(`${group} `)) {
                label = label.replace(new RegExp(`^${group} `), '');
            }

            parts.push({ label, type });
        }
    }

    // considering that parts could have identical results, group those into one value with a total count in parenthesis
    const groupedParts = parts.reduce((acc, part) => {
        const key = `${part.label}-${part.type}`;
        const existingPart = acc.find((p) => `${p.label}-${p.type}` === key);

        if (existingPart) {
            existingPart.count = (existingPart.count || 1) + 1;
        } else {
            acc.push({ ...part, count: 1 });
        }
        return acc;
    }, []);

    return groupedParts
        .map((part) => ({
            ...part,
            label: part.count > 1 ? `${part.label} (${part.count})` : part.label,
            error: !part.type
        }))
        .map((p) => (p.error ? `<span class="uk-text-danger">${p.label}</span>` : p.label));
}

// split the field path into queryField and Field path
function splitFieldPath(name, base) {
    const { Schema } = api.builder.helpers;
    const isListOfType = (f) => f?.type?.kind === 'LIST';

    const parts = Schema.resolvePath(name, base).map(([f]) => f);
    const queryField = parts.find(isListOfType);

    const queryFieldPath = parts
        .slice(0, parts.indexOf(queryField) + 1)
        .map((f) => f.name)
        .join('.');

    const fieldPath = parts
        .slice(parts.indexOf(queryField) + 1)
        .map((f) => f.name)
        .join('.');

    return [queryFieldPath, fieldPath];
}

function getFieldsDefaults(fields) {
    return Object.entries(fields).reduce((def, [name, field]) => {
        if ('default' in field) {
            def[name] = field.default;
        }

        if (field.fields) {
            Object.assign(def, getFieldsDefaults(field.fields));
        }

        return def;
    }, {});
}

function getFieldsKeys(fields) {
    return Object.entries(fields).reduce((keys, [name, field]) => {
        keys.push(name);

        if (field.fields) {
            keys.push(...getFieldsKeys(field.fields));
        }

        return keys;
    }, []);
}
