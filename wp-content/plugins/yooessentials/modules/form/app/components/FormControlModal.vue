<template>
    <div id="yooessentials-form-fields-modal">
        <div class="uk-modal-header">
            {{ $t('Form Fields') }}
        </div>

        <div class="uk-modal-body">
            <table
                v-if="Object.keys(fields).length"
                class="uk-table uk-table-divider"
                :class="{ 'uk-table-hover': selectable }"
            >
                <thead>
                    <tr>
                        <th>{{ $t('Name') }}</th>
                        <th>{{ $t('Type') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="node in fields" :key="Builder.key(node)">
                        <td v-if="selectable" class="uk-text-bold uk-table-link">
                            <a href class="uk-link-reset" @click.prevent="$emit('resolve', node)">{{
                                node.props.control_name
                            }}</a>
                        </td>

                        <td v-else class="uk-text-bold">{{ node.props.control_name }}</td>

                        <td>{{ Builder.type(node).title }}</td>
                    </tr>
                </tbody>
            </table>

            <template v-else>
                {{
                    $t(
                        'This Form Area has no assigned fields yet or those have not been associated with a name.'
                    )
                }}
            </template>
        </div>
    </div>
</template>

<script>
import essentials from 'yooessentials';
import { flattenChildren } from '@yooessentials/util';

export default {
    inject: ['Builder'],

    props: {
        selectable: {
            type: Boolean,
            default: false
        },

        filter: {
            type: String
        }
    },

    computed: {
        fields() {
            const nodes = flattenChildren(essentials.Form.currentFormAreaNode)
                .filter((node) => node.type.match(/yooessentials_form_/))
                .filter((node) => node.props.control_name);

            if (this.filter) {
                return nodes.filter((node) => node.type.match(new RegExp(this.filter)));
            }

            return nodes;
        }
    }
};
</script>
