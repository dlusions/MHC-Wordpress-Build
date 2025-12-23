<template>
    <div class="uk-margin-remove-first-child">
        <div v-if="auths.length">
            <ul class="uk-nav uk-nav-default yo-sidebar-marginless yo-nav-iconnav">
                <ResourceListItem
                    v-for="{ auth, driver, meta, error, warning, expiresSoon } in auths"
                    :key="auth.id"
                    :title="
                        auth.name ||
                        auth.account ||
                        auth.username ||
                        (driver && driver.title) ||
                        $t('Unknown')
                    "
                    :icon="driver && driver.icon"
                    :error="error || warning"
                    :meta="meta"
                    :actions="
                        [
                            expiresSoon && {
                                title: $t('Requires Re-Authentication'),
                                emit: 'edit',
                                icon: 'warning',
                                class: 'uk-text-danger',
                                stack: true
                            },
                            'delete'
                        ].filter(Boolean)
                    "
                    :editable="!error"
                    @edit="edit(auth)"
                    @delete="trash(auth)"
                >
                    <template v-if="!driver" #icon>
                        <span class="uk-margin-small-right" uk-icon="question" ratio="1.2"></span>
                    </template>

                    <template v-else-if="!driver.icon && driver.type !== 'oauth'" #icon>
                        <span class="uk-margin-small-right" uk-icon="lock" ratio="1.2"></span>
                    </template>
                </ResourceListItem>
            </ul>

            <p class="uk-text-muted">
                {{
                    $t(
                        'Connected Accounts and other authentication-related keys used with Sources, Storages or Form Actions.'
                    )
                }}
            </p>
        </div>

        <div v-else class="uk-text-center">
            <p>
                {{
                    $t(
                        'Connected Accounts and other authentication-related keys used with Sources, Storages or Form Actions will appear here.'
                    )
                }}
            </p>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import add from 'date-fns/add';
import essentials from 'yooessentials';
import { ResourceListItem } from 'yooessentials-components';
import AuthModal from './AuthModal.vue';

export default {
    name: 'AuthSection',

    components: {
        ResourceListItem
    },

    computed: {
        drivers() {
            return essentials.helpers.Auth.drivers;
        },

        auths() {
            return essentials.helpers.Auth.auths.map((auth) => {
                const driver = this.drivers?.[auth.driver];

                if (!driver) {
                    return { auth, error: 'Auth Driver Not Found' };
                }

                if (driver.type === 'oauth' && !auth.scopes) {
                    return { auth, driver, warning: 'Missing Authorization' };
                }

                const res = { auth, driver };

                res.meta = driver.title;

                if (driver.accessTokenThreshold && auth.expiresAt) {
                    const expiresAt = new Date(auth.expiresAt?.date || auth.expiresAt * 1000);
                    const expiresIn = expiresAt.valueOf() - new Date().valueOf();
                    const formated = expiresAt.toLocaleString().split(',')[0];

                    if (expiresIn <= 0) {
                        return { ...res, warning: 'Expired ' + formated };
                    }

                    let [num, unit] = driver.accessTokenThreshold.split(' ');

                    // pluralize
                    if (unit[unit.length + 1] !== 's') {
                        unit = `${unit}s`;
                    }

                    const threshold = add(new Date(), { [unit]: num });
                    const thresholdIn = threshold.valueOf() - new Date().valueOf();

                    res.expiresSoon = expiresIn < thresholdIn;
                }

                return res;
            });
        }
    },

    methods: {
        async edit(values) {
            const driver = essentials.helpers.Auth.drivers[values.driver];
            const auth = await api.uikit.promptModal(AuthModal, {
                driver,
                values,
                scopes: Object.keys(driver?.scopes || {})
            });

            if (auth) {
                essentials.helpers.Auth.save(auth);
            }
        },

        trash(auth) {
            essentials.helpers.Auth.remove(auth);
        }
    }
};
</script>
