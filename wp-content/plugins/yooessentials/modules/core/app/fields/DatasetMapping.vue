<template>
    <div v-if="attributes.disabled"></div>

    <div v-else-if="fetchError" class="uk-text-danger">
        {{ fetchError }}
    </div>

    <div v-else-if="fetching || !fetched.length">{{ $t('Fetching') }} ...</div>

    <ul
        v-else-if="!isEmpty(fetched)"
        :class="[
            'uk-nav uk-nav-default yo-sidebar-marginless uk-margin uk-margin-remove-bottom',
            { 'yo-nav-iconnav': !attributes.disabled }
        ]"
    >
        <ResourceListItem
            v-for="({ item, title, icon, meta, error, disabled, actions, indications }, i) in items"
            :key="i"
            :title="title"
            :icon="icon"
            :meta="meta"
            :error="error"
            :actions="actions"
            :indications="indications"
            :disabled="Boolean(attributes.disabled || disabled)"
            @edit="edit(item)"
            @copy="copy(item)"
            @delete="remove(item)"
            @toggle="toggle(item)"
        />
    </ul>
</template>

<script>
import http from '@yootheme/http';
import DatasetField from './Dataset.vue';
import { isArray, isEmpty, isString } from 'uikit-util';
import { debounce, get } from '../util';

export default {
    name: 'DatasetMappingField',

    extends: DatasetField,

    data: () => ({
        fetching: false,
        fetched: [],
        fetchError: false
    }),

    computed: {
        watching() {
            return this.field.watch?.split(',') || [];
        },

        items() {
            return this.value.map((item) => {
                const fetched = this.fetched.find(({ id }) => id === item.id);
                const isMapped = Boolean(
                    item.props?.value || item.source?.props || item.source_extended?.props
                );
                const isDisabled = item.props?.status === 'disabled';
                const isRequired = Boolean(fetched.required);

                return {
                    item,
                    title: this.getTitle(item),
                    icon: get(fetched, this.field.iconMap || 'icon'),
                    meta: get(fetched, this.field.metaMap || 'meta'),
                    disabled: isDisabled,
                    indications: [
                        !isDisabled && {
                            title: 'Status',
                            icon: `ye--${isMapped ? 'check-circle' : 'circle-dashed'}`
                        }
                    ].filter(Boolean),
                    actions: [
                        !isRequired && {
                            title: 'Toggle',
                            emit: 'toggle',
                            icon: `ye--toggle-${isDisabled ? 'off' : 'on'}`,
                            stack: true
                        }
                    ].filter(Boolean)
                };
            });
        }
    },

    watch: {
        fetched(fetched) {
            this.value = [...fetched]
                .sort((a, b) => {
                    return a.required === b.required ? 0 : a.required ? -1 : 1;
                })
                .map(({ id, required }) => {
                    const item = this.value.find((v) => v.id === id) || {
                        props: {
                            status: !required ? 'disabled' : ''
                        }
                    };

                    return { id, ...item };
                });
        }
    },

    mounted() {
        // this.$watch('attributes.disabled', disabled => {
        //     disabled || this.fetch();
        // }, {immediate: true});

        this.watching.forEach((watch) => {
            this.$watch(
                `values.${watch}`,
                debounce(() => {
                    this.fetch();
                }, 100)
            );
        });

        if (!this.attributes.disabled) {
            this.fetch();
        }
    },

    methods: {
        isEmpty,

        fetch() {
            this.fetching = true;

            http(this.field.endpoint)
                .post({})
                .json((data) => {
                    if (isArray(data)) {
                        this.fetched = data;
                        this.fetchError = data.length === 0 ? 'No Mapping Fields' : false;
                    } else {
                        this.fetched = [];
                        this.fetchError = isString(data) ? data : 'Error';
                    }

                    this.fetching = false;
                })
                .catch((e) => {
                    this.fetched = [];
                    this.fetchError = e?.json || e?.message || e?.toString() || 'Error';
                    this.fetching = false;
                });
        },

        toggle(item) {
            if (!item.props) {
                this.$set(item, 'props', {});
            }

            const isDisabled = item.props.status === 'disabled';

            this.$set(item.props, 'status', isDisabled ? '' : 'disabled');
        },

        edit(item) {
            if (!item.props) {
                this.$set(item, 'props', {});
            }

            this.openPanel({
                ...this.getPanel(item),
                name: 'dataset-panel',
                title: this.getTitle(item),
                props: {
                    node: item,
                    values: item.props
                }
            });
        },

        getTitle(item) {
            const fetched = this.fetched.find(({ id }) => id === item.id);
            const map = this.field.titleMap || 'title';
            return get(fetched, map) || get(item, map) || this.field.titleFallback || 'Item';
        },

        getMeta(item) {
            const fetched = this.fetched.find(({ id }) => id === item.id);
            return get(fetched, this.field.metaMap || 'meta');
        },

        getPanel(item) {
            let panel = this.field.panel;

            if (!panel) {
                return {
                    title: 'Content',
                    fields: {
                        value: {
                            label: 'Value',
                            source: true,
                            description: 'The value for this data entry.'
                        }
                    }
                };
            }

            if (isString(panel)) {
                const getPanel = (panel) =>
                    this.Sidebar?.panel?.panels?.[panel] || this.Sidebar?.panels?.[panel];
                const panelName = this.resolvePanelName(panel, item);

                panel = getPanel(panelName);

                if (!panel && this.field.panelFallback) {
                    panel = getPanel(this.field.panelFallback);
                }

                if (!panel) {
                    throw new Error('Panel not found: ' + panelName);
                }
            }

            return panel;
        },

        resolvePanelName(panel, item = {}) {
            const matches = Array.from(panel.matchAll(/\{(.*?[a-zA-Z]+.*?)\}/g));

            matches.forEach((match) => {
                const fetched = this.fetched.find(({ id }) => id === item.id);
                const path = match[1];

                panel = panel.replace(match[0], get(fetched, path) || get(item, path) || '');
            });

            return panel;
        }
    }
};
</script>
