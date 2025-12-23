<template>
    <div>
        <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
            <thead>
                <tr>
                    <th colspan="2">{{ $t('Icon') }}</th>
                    <th class="uk-table-shrink">{{ $t('Collection') }}</th>
                    <th class="uk-table-shrink">{{ $t('Group') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="{ name, group, collection, key } in icons"
                    :key="key"
                    class="uk-visible-hover"
                    @click="$emit('resolve', key)"
                >
                    <td class="uk-table-shrink uk-text-center">
                        <span class="yo-finder-list-image uk-icon-image" :uk-icon="key"></span>
                    </td>
                    <td class="uk-table-expand uk-text-nowrap">
                        {{ name }}
                    </td>
                    <td class="uk-text-nowrap">
                        {{ collection }}
                    </td>
                    <td class="uk-text-nowrap">
                        {{ group }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        icons: {
            type: Array,
            required: true
        }
    }
};
</script>
