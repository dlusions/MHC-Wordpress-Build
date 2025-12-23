<template>
    <div>
        <p v-if="!hasChildren" class="uk-text-muted">
            {{ $t(field.txtEmpty || 'No items yet.') }}
        </p>

        <ul
            v-else
            v-sortable="{ group: 'content-items' }"
            class="uk-nav uk-nav-default yo-sidebar-marginless yo-nav-sortable yo-nav-iconnav uk-margin"
            cls-custom="yo-nav-sortable-drag"
        >
            <ContentItem
                v-for="child in $node.children"
                :key="Builder.key(child)"
                ref="items"
                :node="child"
                :field="field"
            />
        </ul>

        <div class="uk-grid uk-grid-small uk-child-width-auto">
            <div>
                <button
                    v-if="field.item"
                    class="uk-button uk-button-default"
                    type="button"
                    @click="add(field.item)"
                >
                    {{ $t(field.button || 'Add Item') }}
                </button>
                <button
                    v-else
                    class="uk-button uk-button-default"
                    type="button"
                    @click="addFromLibrary"
                >
                    {{ $t(field.button || 'Add Item') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import ContentItem from './ContentItem.vue';
import { DropdownList } from '../components';

export default {
    name: 'ContentItems',

    components: {
        ContentItem
    },

    extends: fields.components.FieldContentItems,

    computed: {
        hasChildren() {
            return this.$node?.children?.length;
        }
    },

    methods: {
        async addFromLibrary() {
            const filter = this.field.filter;
            let elements = Object.values(this.Builder.types);

            if (filter?.group) {
                elements = elements.filter((el) => el.group === filter.group);
            }

            if (filter?.name) {
                elements = elements.filter((el) => el?.name?.match(new RegExp(filter.name)));
            }

            if (elements.length === 1) {
                this.addItem(elements[0].name);
                return;
            }

            const value = await api.uikit.promptDropdown(
                DropdownList,
                {
                    entries: elements.map((type) => ({
                        value: type.name,
                        text: type.title,
                        meta: type.name,
                        icon: type.iconSmall
                    }))
                },
                this.$el,
                { classes: 'yo-dropdown', flip: false }
            );

            if (value) {
                this.addItem(value);
            }
        },

        addItem(name) {
            this.Builder.add(this.$node, name, this.Builder.append).then((item) =>
                this.Builder.edit(item)
            );
        }
    }
};
</script>
