<template>
    <input v-if="attributes.disabled" disabled class="uk-input" />

    <div v-else>
        <div class="uk-inline uk-width-1-1">
            <div class="uk-position-center-right uk-position-small">
                <ul class="uk-iconnav uk-flex-nowrap">
                    <li>
                        <a
                            tabindex="-1"
                            :title="$t('Pick')"
                            class="uk-icon-link uk-preserve-width"
                            uk-icon="album"
                            uk-tooltip="delay: 500"
                            @click.prevent="open"
                        ></a>
                    </li>
                </ul>
            </div>

            <input
                v-model="value"
                type="text"
                class="uk-input yo-input-iconnav-right"
                v-bind="attributes"
            />
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import { DropdownList } from '../components';
import { stringIncludes } from '../util';

export default {
    name: 'InputDrop',

    extends: fields.components.FieldText,

    inject: {
        Sidebar: {
            default: {}
        }
    },

    data: () => ({
        items: [],
        search: ''
    }),

    created() {
        if (this.field.items) {
            this.items = this.field.items;
        }
    },

    methods: {
        async open() {
            const params = { ...this.field.params };

            this.$trigger('yooessentials-resolve-field-argument', params);

            const value = await api.uikit.promptDropdown(
                {
                    functional: true,
                    render: (h, { listeners }) =>
                        h(DropdownList, {
                            props: {
                                search: this.search,
                                maxHeight: this.field.dropsize,
                                entries: this.items.filter((item) =>
                                    stringIncludes(item.text, this.search)
                                )
                            },
                            on: {
                                'update:search': (val) => {
                                    this.search = val;
                                },
                                resolve: (item) => {
                                    listeners.resolve(item);
                                }
                            }
                        })
                },
                {},
                this.$el,
                { classes: 'yo-dropdown', flip: false }
            );

            if (value) {
                this.value = value;
            }
        }
    }
};
</script>
