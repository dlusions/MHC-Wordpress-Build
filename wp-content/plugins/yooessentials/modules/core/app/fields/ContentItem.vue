<template>
    <Item
        :status-icons="statusIcons"
        :title="thetitle"
        :icon="icon"
        @edit="Builder.edit(node)"
        @copy="Builder.copy(node)"
        @remove="Builder.remove(node)"
    />
</template>

<script>
import BaseFields from '@yootheme/fields';
import { Item } from '../components';
import { fragment } from 'uikit-util';

export default {
    name: 'ContentItem',

    components: {
        Item
    },

    extends: BaseFields.components.FieldContentItems.components.ContentItem,

    computed: {
        icon() {
            return this.field.icon ? this.type.iconSmall : '';
        },

        thetitle() {
            const title = this.field.title ? this.field.title.split('||') : ['title'];

            for (let _title of title) {
                _title = _title.trim();

                // if select field, evaluate selection
                if (this.type.fields[_title]?.type === 'select') {
                    const titleValue = this.node.props[_title];
                    const options = this.type.fields[_title].options;
                    return Object.keys(options).filter((key) => options[key] === titleValue)[0];
                }

                if (this.node?.props?.[_title]) {
                    return stripTags(this.node.props[_title]);
                }
            }

            return this.type.title || '';
        }
    }
};

function stripTags(html) {
    return fragment(html)?.textContent;
}
</script>
