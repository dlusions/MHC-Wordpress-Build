/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';
import { isEmpty } from 'uikit-util';
import essentials from 'yooessentials';
import { set } from '@yooessentials/util';
import SchemaHelper from './SchemaHelper';

export default {
    nodeKey: '#node',
    parentKey: '#parent',
    closestKey: '~closest',
    globalKey: '~global:',

    showMultipleSelectField(source) {
        const baseSchemaField = SchemaHelper.findField(source.queryPath);
        const isMultipleSource = baseSchemaField?.type?.kind === 'LIST';
        const hasListFields = api.builder.helpers.Schema.hasFields(baseSchemaField, 'LIST');

        return source.values.query?.field?.name || (!isMultipleSource && hasListFields);
    },

    getQuery(node) {
        return api.builder.helpers.Source.getQuery(node);
    },

    getProp(node, field) {
        return node.source?.props?.[field.name] || node.source_extended?.props?.[field.name];
    },

    removeProp(node, field) {
        this.removeQueryProp(node, field.name);
    },

    removeQueryProp(node, name) {
        // source
        if (node?.source?.props?.[name]) {
            Vue.delete(node.source.props, name);
        }
        if (node.source?.props && isEmpty(node.source.props)) {
            Vue.delete(node.source, 'props');
        }
        if (node.source && isEmpty(node.source)) {
            Vue.delete(node, 'source');
        }

        // source extended
        if (node?.source_extended?.props?.[name]) {
            Vue.delete(node.source_extended.props, name);
        }
        if (node.source_extended?.props && isEmpty(node.source_extended.props)) {
            Vue.delete(node.source_extended, 'props');
        }
        if (node.source_extended && isEmpty(node.source_extended)) {
            Vue.delete(node, 'source_extended');
        }
    },

    getQueryArgumentProp(node, name) {
        return node.source?.query?.arguments_extended?.[name];
    },

    removeQueryArgumentProp(node, name) {
        for (const prop of ['arguments', 'arguments_extended']) {
            if (node?.source?.query?.[prop]?.[name]) {
                Vue.delete(node.source.query[prop], name);

                if (isEmpty(node.source.query[prop])) {
                    Vue.delete(node.source.query, prop);
                }
            }
        }
    },

    getQueryFieldArgumentProp(node, name) {
        return node.source?.query?.field?.arguments_extended?.[name];
    },

    removeQueryFieldArgumentProp(node, name) {
        if (node?.source?.query?.field?.arguments_extended?.[name]) {
            Vue.delete(node.source.query.field.arguments_extended, name);

            if (isEmpty(node.source.query.field.arguments_extended)) {
                Vue.delete(node.source.query.field, 'arguments_extended');
            }
        }
    },

    setQuery(node, name) {
        Vue.delete(node?.source || {}, 'query');
        Vue.delete(node?.source_extended || {}, 'query');

        if (name) {
            set(node, 'source.query.name', name);
        }

        if (isEmpty(node.source)) {
            Vue.delete(node, 'source');
        }

        if (isEmpty(node.source_extended)) {
            Vue.delete(node, 'source_extended');
        }
    },

    setProp(node, field, values) {
        if (field.origin === 'query-args') {
            this.setQueryArgumentProp(node, field.name, values);
            return;
        }

        if (field.origin === 'query-args-field') {
            this.setQueryFieldArgumentProp(node, field.name, values);
            return;
        }

        this.setExtendedProp(node, field, values);
    },

    setExtendedProp(node, field, values) {
        if (node.props) {
            Vue.delete(node.props, field.name);
        }

        this.removeProp(node, field);

        set(node, `source_extended.props.${field.name}`, values);
    },

    setQueryArgumentProp(node, propName, values) {
        this.removeQueryArgumentProp(node, propName);

        set(node, `source.query.arguments_extended.${propName}`, values);
    },

    setQueryFieldArgumentProp(node, propName, values) {
        this.removeQueryFieldArgumentProp(node, propName);

        set(node, `source.query.field.arguments_extended.${propName}`, values);
    },

    isParentInheriting(query) {
        return query?.name?.startsWith(api.builder.helpers.Source.parentKey);
    },

    resolveAdjacentNode(node) {
        if (!essentials.yoo.Builder?.exists(node) && essentials.yoo.Builder?.adjacentOf) {
            return essentials.yoo.Builder.adjacentOf;
        }

        return node;
    },

    builderPath(source, node) {
        const nodes = essentials.yoo.Builder.path(node);

        // remove the current node?
        if (!source?.isNodeInheriting) {
            nodes.splice(0, 1);
        }

        // add source as a fake child node
        let query = source.values.query?.name ?? this.parentKey;

        const fakeSourceNode = {
            name: '#source',
            source: {
                query: {
                    ...(source.values.query ?? {}),
                    name: query.replace(this.nodeKey, this.parentKey)
                }
            }
        };

        nodes.unshift(fakeSourceNode);

        // resolve global queries,
        // for each global query slice in a fake node with the resolved query,
        // and change the node query to inherit from parent, that way the final
        // path will take into consideration the global query field as well
        for (let i = 0; i < nodes.length; i++) {
            const n = nodes[i];

            if (this.isGlobalQuery(n.source?.query)) {
                const globalQuery = this.resolveGlobalQuery(n.source.query);

                if (globalQuery) {
                    // Create a fake node with the resolved global query
                    const fakeNode = {
                        name: '#global',
                        source: {
                            query: globalQuery
                        }
                    };

                    // the layout must not be changed!
                    // n.source.query.name = this.parentKey;

                    // Insert the fake node after the current node
                    nodes.splice(i + 1, 0, fakeNode);

                    // Skip the fake node we just inserted
                    i++;
                }
            }
        }

        return nodes.filter(Boolean);
    },

    getGlobalQuery(query) {
        const id = query.name.replace(this.globalKey, '');
        return essentials.helpers.Dynamic.getGlobalQuery(id);
    },

    resolveGlobalQuery(query) {
        return this.getGlobalQuery(query)?.source?.query;
    },

    isGlobalQuery(query) {
        return query?.name?.startsWith(essentials.helpers.Source.globalKey);
    }
};
