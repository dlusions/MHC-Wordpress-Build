<template>
    <div v-if="isEmpty(value)" class="uk-margin">
        <div>{{ $t(field.txtEmpty || 'No items yet.') }}</div>

        <button
            class="uk-button uk-button-default uk-margin-top"
            type="button"
            @click.prevent="add"
        >
            {{ $t(field.txtAdd || 'Add') }}
        </button>
    </div>

    <div v-else>
        <ResourceList :sortable="isSortable" :disabled="isDisabled">
            <ResourceListItem
                v-for="{ item, id, disabled, ...props } in items"
                :key="id"
                v-bind="props"
                :disabled="Boolean(isDisabled || disabled)"
                @edit="edit(item)"
                @copy="copy(item)"
                @delete="remove(item)"
            />
        </ResourceList>

        <hr class="uk-margin-remove" />

        <div class="uk-flex uk-flex-left uk-margin-small-top">
            <button
                :disabled="isDisabled"
                type="button"
                class="uk-button uk-button-small uk-button-default"
                @click.prevent="add"
            >
                {{ $t(field.txtAdd || 'Add') }}
            </button>
        </div>
    </div>
</template>

<script>
import fields from '@yootheme/fields';
import ResourcesMixin from './mixins/resources';
import { get, uuid } from '../util';
import { isEmpty, isString, isUndefined } from 'uikit-util';

export default {
    name: 'DatasetField',

    extends: fields.components.FieldText,

    mixins: [ResourcesMixin],

    inject: {
        Sidebar: { default: () => ({}) },
        Builder: { default: undefined },
        $node: { default: undefined }
    },

    computed: {
        items() {
            return this.value.map((item) => ({
                item,
                id: uuid(),
                title: this.getTitle(item),
                disabled: item.props?.status === 'disabled',
                actions: this.isDisabled
                    ? []
                    : [this.isCopyable ? 'copy' : null, this.isDeletable ? 'delete' : null]
            }));
        },

        isDisabled() {
            return Boolean(this.field.attrs?.disabled);
        },

        isSortable() {
            return isUndefined(this.field.sortable) || this.field.sortable;
        },

        isCopyable() {
            return isUndefined(this.field.copyable) || this.field.copyable;
        },

        isDeletable() {
            return isUndefined(this.field.deletable) || this.field.deletable;
        }
    },

    methods: {
        isEmpty,

        add() {
            this.push({});
            this.edit(this.value[this.value.length - 1]);
        },

        edit(item) {
            if (!item.props) {
                this.$set(item, 'props', {});
            }

            this.openPanel({
                title: this.getTitle(item),
                name: 'dataset-panel',
                ...this.getPanel(this.field.panel),
                props: {
                    node: item,
                    values: item.props
                }
            });
        },

        openPanel(panel) {
            this.$trigger('openDynamicPanel', {
                ...panel,
                heading: !panel.fieldset,
                adjacentOf: this.$node
            });
        },

        getTitle(item) {
            const name = this.field?.titleMap || 'name';
            const getProp = (prop) => get(item.props, prop) || get(item, prop);

            let value = getProp(name);

            // if (!value) {
            //     value = this.resolveTemplate(name, getProp);
            // }

            return value || this.field.titleFallback || this.$t('Item');
        },

        resolveTemplate(template, getProp) {
            return template.replace(/\{([^}]+)\}/g, (match, propName) => {
                return getProp(propName.trim()) || '';
            });
        },

        getPanel(panel) {
            if (isString(panel)) {
                panel = this.Sidebar?.panel?.panels?.[panel] || this.Sidebar?.panels?.[panel];
            }

            if (!panel) {
                return {};
            }

            return panel;
        }
    }
};
</script>
