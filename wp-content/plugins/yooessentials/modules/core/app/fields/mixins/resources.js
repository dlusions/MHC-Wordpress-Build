/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { isArray } from 'uikit-util';
import { deepCopy, reindex, uuid } from '../../util';
import { ResourceList } from '../../components';
import { ResourceListItem } from '../../components';

export default {
    components: {
        ResourceList,
        ResourceListItem
    },

    idPrefix: '',

    created() {
        if (!isArray(this.value)) {
            this.value = [];
        }

        this.value.forEach((val, i) => {
            if (!val.id) {
                // workaround for resources created before the id concept
                this.$set(this.value, i, { id: this.getId(), ...val });
            }

            if (isArray(val.props)) {
                this.$set(this.value, i, { ...val, props: {} });
            }
        });
    },

    methods: {
        getId() {
            return `${this.$options.idPrefix}${uuid()}`;
        },

        push(value) {
            value.id = this.getId();

            this.value = [...this.value, value];
        },

        remove(item) {
            this.value.splice(this.value.indexOf(item), 1);
        },

        copy(item) {
            const newItem = deepCopy(item);

            if (newItem.id) {
                newItem.id = this.getId();
            }

            if (newItem.props?.name) {
                newItem.props.name = `${newItem.props.name} Copy`;
            }

            this.value.splice(this.value.indexOf(item) + 1, 0, newItem);
        },

        move(child, parent, toIndex) {
            const fromIndex = parent.$children.indexOf(child);

            reindex(this.value, fromIndex, toIndex);
        }
    }
};
