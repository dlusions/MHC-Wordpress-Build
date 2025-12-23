<template>
    <div v-if="!field.panel" class="uk-text-danger">Panel not specified</div>

    <button
        v-else-if="isEmpty(value)"
        class="uk-button yo-button-panel yooessentials-button-plus uk-width-1-1"
        type="button"
        @click.prevent="add"
    >
        {{ $t(field.txtEmpty || 'Add') }}
    </button>

    <div v-else>
        <ul
            v-sortable="{ group: 'settings-items' }"
            class="uk-nav uk-nav-default yo-sidebar-marginless yo-nav-sortable yo-nav-iconnav uk-margin uk-margin-remove-bottom"
            cls-custom="yo-nav-sortable-drag"
        >
            <Item
                v-for="(item, i) in value"
                :key="i"
                :title="getItemTitle(item)"
                @edit="edit(i)"
                @copy="copy(i)"
                @remove="remove(i)"
            />
        </ul>

        <div>
            <hr class="uk-margin-small-bottom" />

            <div class="uk-flex uk-flex-right">
                <a
                    href
                    :title="$t(field.txtEmpty || 'Add')"
                    uk-tooltip="delay: 1000"
                    class="ye-builder-icon-add"
                    @click.prevent="add"
                ></a>
            </div>
        </div>
    </div>
</template>

<script>
import fields from '@yootheme/fields';
import { deepCopy, reindex, uuid } from '../util';
import { Item } from '../components';
import { findIndex, fragment, isArray, isEmpty, isString } from 'uikit-util';

export default {
    name: 'SettingsPanel',

    components: {
        Item
    },

    extends: fields.components.FieldContentItems.extends,

    inject: {
        Sidebar: {},
        Builder: { default: undefined },
        $node: { default: undefined }
    },

    created() {
        if (!isArray(this.value)) {
            this.value = [];
        }
    },

    methods: {
        isEmpty,

        add() {
            this.push({});
            this.edit(this.value.length - 1);
        },

        edit(index) {
            const node = this.value[index];

            if (this.Builder) {
                this.Builder.adjacentOf = this.field?.adjacent ? this.$node : null;
            }

            this.$trigger('openDynamicPanel', {
                ...this.getPanel(this.field.panel),
                name: uuid(), // must be unique for proper breadcrumb
                heading: false,
                props: {
                    node,
                    values: node,
                    builder: this.Builder,
                    rootPanelValues: this.values
                }
            });
        },

        push(value) {
            this.value = [...this.value, value];
        },

        remove(index) {
            this.value.splice(index, 1);
        },

        copy(index) {
            this.value.splice(index, 0, deepCopy(this.value[index]));
        },

        move(child, parent, toIndex) {
            const fromIndex = findIndex(parent.$children, (c) => c === child);
            reindex(this.value, fromIndex, toIndex);
        },

        getPanel(panel) {
            if (!panel) {
                throw new Error('Invalid Panel');
            }

            if (isString(panel)) {
                panel = {
                    name: panel,
                    // get panel from current panel, fallback to global panels
                    ...(this.Sidebar?.panel?.panels?.[panel] || this.Sidebar?.panels?.[panel])
                };
            }

            if (!panel.name) {
                throw new Error('Panel is missing name');
            }

            return panel;
        },

        getItemTitle(item) {
            if (isString(this.field?.title)) {
                this.field.title = { prop: this.field.title };
            }

            const title = { prop: 'title', default: 'Item', ...(this.field?.title ?? {}) };

            return this.stripTags(item[title.prop] || title.default);
        },

        stripTags(html) {
            return fragment(html)?.textContent;
        }
    }
};
</script>
