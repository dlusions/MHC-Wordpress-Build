<template>
    <div>
        <div class="yo-dropdown-body uk-overflow-auto uk-height-max-large">
            <ul class="uk-nav uk-dropdown-nav">
                <li
                    v-for="{ auth, driver } in $auths"
                    :key="auth.id"
                    class="uk-visible-toggle uk-text-truncate uk-position-relative"
                >
                    <a href @click.prevent="$emit('resolve', auth)">
                        <img
                            v-if="driver.icon"
                            :alt="driver.title"
                            class="uk-margin-small-right"
                            :src="driver.icon"
                            uk-svg
                            width="20"
                            height="20"
                        />
                        <span v-else uk-icon="lock" class="uk-margin-small-right"></span>

                        <div class="uk-text-baseline">
                            <span class="uk-text-emphasis">{{
                                auth.name ||
                                auth.account ||
                                auth.username ||
                                driver.title ||
                                driver.name
                            }}</span>
                            <span class="uk-display-block uk-text-meta">
                                {{ driver.name }}
                                <span v-if="auth.expiresAt"
                                    >{{ $t('Expires') }} {{ auth.expiresAt | date }}</span
                                >
                            </span>
                        </div>
                    </a>

                    <div
                        class="uk-invisible-hover uk-position-center-right uk-position-medium uk-margin-small-right"
                    >
                        <ul class="uk-iconnav uk-flex-nowrap">
                            <li>
                                <a
                                    :title="$t('Edit')"
                                    class="uk-icon-link uk-preserve-width"
                                    uk-icon="pencil"
                                    uk-tooltip="delay: 500"
                                    @click.prevent="$emit('edit', auth)"
                                ></a>
                            </li>
                            <li>
                                <a
                                    :title="$t('Delete')"
                                    class="uk-icon-link uk-preserve-width"
                                    uk-icon="trash"
                                    uk-tooltip="delay: 500"
                                    @click.prevent="$emit('remove', auth)"
                                ></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li v-if="auths.length" class="uk-nav-divider"></li>

                <template v-for="{ driver } in $connections">
                    <li v-if="driver" :key="driver.name">
                        <a href @click.prevent="$emit('add', driver.name)">
                            <span class="uk-margin-small-right" uk-icon="plus-circle"></span>
                            {{ driver.title }}
                            <span class="uk-text-meta">{{ driver.description }}</span>
                        </a>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
import { format } from 'date-fns';
import { includes } from 'uikit-util';

export default {
    name: 'AuthDropdown',

    filters: {
        date(dateValue) {
            if (!dateValue) return '';

            // Handle Unix timestamp (number)
            if (typeof dateValue === 'number') {
                dateValue = new Date(dateValue * 1000);
            }

            // Handle date object with date property
            if (dateValue?.date) {
                dateValue = new Date(dateValue.date);
            }

            return format(dateValue, 'do MMM, yyyy');
        }
    },

    props: {
        drivers: {
            type: Object,
            required: true
        },

        auths: {
            type: Array,
            required: true
        },

        connections: {
            type: Object,
            default: () => ({})
        }
    },

    computed: {
        $auths() {
            return this.auths.map((auth) => {
                const scopes = this.connections[auth.driver] || [];
                const driver = { services: [], ...this.drivers?.[auth.driver] };
                const granted = this.validateScopes(auth, scopes);

                return { auth, driver, granted };
            });
        },

        $connections() {
            return Object.keys(this.connections)
                .filter((d) => this.drivers?.[d])
                .map((d) => {
                    const scopes = this.connections[d];
                    const driver = { services: [], ...this.drivers[d] };

                    return { driver, scopes };
                });
        }
    },

    methods: {
        validateScopes(auth, scopes) {
            return scopes?.every((scope) => includes(auth.scopes, scope));
        }
    }
};
</script>
