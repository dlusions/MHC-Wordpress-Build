<template>
    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
        <thead>
            <tr>
                <th class="uk-table-shrink">
                    <input
                        :checked="Finder.selectedAll"
                        class="uk-checkbox"
                        type="checkbox"
                        @change="Finder.toggleSelectAll()"
                    />
                </th>
                <th class="uk-table-shrink uk-text-center">{{ $t('Type') }}</th>
                <th>{{ $t('Name') }}</th>
                <th class="uk-table-shrink">{{ $t('Created') }}</th>
                <th class="uk-table-shrink">{{ $t('Modified') }}</th>
                <th class="uk-table-shrink"></th>
            </tr>
        </thead>

        <tbody>
            <tr
                v-for="node in Finder.items"
                :key="node.id"
                :class="{ 'uk-active': Finder.isSelected(node) }"
                class="uk-visible-toggle"
            >
                <td>
                    <input
                        :checked="Finder.isSelected(node)"
                        class="uk-checkbox"
                        type="checkbox"
                        @click="Finder.toggleSelect(node)"
                    />
                </td>
                <td class="uk-table-shrink uk-text-center">
                    <span
                        v-if="node.type === 'layout'"
                        class="yo-finder-list-image uk-icon-image"
                        uk-icon="copy"
                        :uk-tooltip="$t('Layout')"
                    ></span>

                    <span
                        v-else-if="node.type === 'section'"
                        class="yo-finder-list-image uk-icon-image"
                        uk-icon="file"
                        :uk-tooltip="$t('Section')"
                    ></span>

                    <span v-else-if="Builder.exists(node)" :uk-tooltip="Builder.type(node).title">
                        <img
                            :alt="node.name"
                            :src="Builder.type(node).iconSmall"
                            class="uk-text-muted uk-preserve-width uk-margin-small-right"
                            uk-svg
                        />
                    </span>
                </td>
                <td class="uk-table-expand uk-text-nowrap uk-table-link">
                    <a class="uk-link-heading" @click="Finder.$emit('select', node)">{{
                        node.name
                    }}</a>
                </td>
                <td class="uk-text-nowrap">
                    {{ node.created | date }}
                </td>
                <td class="uk-text-nowrap">
                    {{ node.modified | date }}
                </td>
                <td>
                    <ul class="uk-iconnav uk-flex-nowrap uk-invisible-hover">
                        <li>
                            <button
                                :title="$t('Download')"
                                class="uk-icon-link uk-preserve-width"
                                uk-icon="download"
                                uk-tooltip="delay: 500"
                                @click="Finder.$emit('export', node)"
                            ></button>
                        </li>
                        <li v-if="Finder.isWritable">
                            <button
                                :title="$t('Rename')"
                                class="uk-icon-link uk-preserve-width"
                                uk-icon="pencil"
                                uk-tooltip="delay: 500"
                                @click="Finder.$emit('rename', node)"
                            ></button>
                        </li>
                        <li v-if="Finder.isWritable">
                            <button
                                v-confirm="'Are you sure?'"
                                :title="$t('Delete')"
                                class="uk-icon-link uk-preserve-width"
                                uk-icon="trash"
                                uk-tooltip="delay: 500"
                                @click="Finder.$emit('delete', node)"
                            ></button>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import essentials from 'yooessentials';
import parseISO from 'date-fns/parseISO';
import formatDistanceToNow from 'date-fns/formatDistanceToNow';

export default {
    name: 'ListView',

    directives: {
        confirm: 'confirm'
    },

    filters: {
        date(value) {
            return value && formatDistanceToNow(parseISO(value), { addSuffix: true });
        }
    },

    inject: ['Finder'],

    computed: {
        Builder() {
            return essentials.yoo.Builder;
        }
    }
};
</script>
