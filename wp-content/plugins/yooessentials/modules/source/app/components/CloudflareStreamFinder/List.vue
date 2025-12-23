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
                <th colspan="2">{{ $t('Video') }}</th>
                <th class="uk-table-shrink">{{ $t('Status') }}</th>
                <th class="uk-table-shrink">{{ $t('Duration') }}</th>
                <th class="uk-table-shrink">{{ $t('Created') }}</th>
                <!-- <th class="uk-table-shrink">{{ $t('Size') }}</th> -->
            </tr>
        </thead>

        <tbody>
            <tr
                v-for="item in Finder.items"
                :key="item.id"
                :class="{ 'uk-active': Finder.isSelected(item) }"
                class="uk-visible-hover"
                @click="Finder.toggleSelect(item)"
            >
                <td>
                    <input :checked="Finder.isSelected(item)" class="uk-checkbox" type="checkbox" />
                </td>
                <td class="uk-table-shrink uk-text-center">
                    <span
                        class="yo-finder-list-image uk-icon-image"
                        :uk-icon="item.requireSignedURLs ? 'lock' : 'unlock'"
                        :uk-tooltip="$t(item.requireSignedURLs ? 'Private' : 'Public')"
                    ></span>
                </td>
                <td class="uk-table-expand uk-text-nowrap">
                    {{ item.meta.name }}
                </td>
                <td class="uk-text-nowrap">
                    {{ item | status }}
                </td>
                <td class="uk-text-nowrap">
                    {{ item.duration | duration }}
                </td>
                <td class="uk-text-nowrap">
                    {{ item.created | date }}
                </td>
                <!-- <td class="uk-text-nowrap">
                    {{ item.size | size }}
                </td> -->
            </tr>
        </tbody>
    </table>
</template>

<script>
import Vue from 'vue';
import add from 'date-fns/add';
import parseISO from 'date-fns/parseISO';
import intervalToDuration from 'date-fns/intervalToDuration';
import formatDistanceToNow from 'date-fns/formatDistanceToNow';

export default {
    filters: {
        status(item) {
            const status = {
                inprogress: Vue.i18n.t('In Progress'),
                ready: Vue.i18n.t('Ready')
            };

            return status[item?.status] || status.ready;
        },

        date(value) {
            return formatDistanceToNow(parseISO(value), { addSuffix: true });
        },

        duration(seconds) {
            const start = new Date();
            const format = { hours: 'y', minutes: 'm', seconds: 's' };
            const duration = intervalToDuration({ start, end: add(start, { seconds }) });

            return Object.keys(duration)
                .reduce((carry, unit) => {
                    return duration[unit] ? [...carry, `${duration[unit]}${format[unit]}`] : carry;
                }, [])
                .join(' ');
        },

        size(value) {
            const i = Math.floor(Math.log(value) / Math.log(1024));
            return (
                (value / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
            );
        }
    },

    inject: ['Finder']
};
</script>
