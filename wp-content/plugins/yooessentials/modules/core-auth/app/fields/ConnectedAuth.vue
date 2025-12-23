<template>
    <div v-if="isEmpty(field.connections)">No connections set.</div>

    <div v-else-if="isEmpty(drivers)">No valid drivers.</div>

    <div v-else class="uk-inline uk-width-1-1" @click.self.prevent="empty ? add() : select()">
        <div class="uk-position-center-right uk-position-small">
            <ul class="uk-iconnav uk-flex-nowrap">
                <li v-if="authorizationRequired">
                    <a
                        tabindex="-1"
                        :title="$t('Insufficient Scope Access')"
                        class="uk-icon-link uk-preserve-width uk-text-danger"
                        uk-icon="warning"
                        uk-tooltip="delay: 500"
                        @click.prevent="edit()"
                    ></a>
                </li>
                <li v-else-if="value">
                    <a
                        tabindex="-1"
                        :title="$t('Edit')"
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="pencil"
                        uk-tooltip="delay: 500"
                        @click.prevent="edit()"
                    ></a>
                </li>
                <li>
                    <a
                        tabindex="-1"
                        :title="$t('Pick')"
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="album"
                        uk-tooltip="delay: 500"
                        @click.prevent="empty ? add() : select()"
                    ></a>
                </li>
            </ul>
        </div>

        <div
            class="uk-input uk-cursor-default uk-disabled"
            :title="attributes.title"
            :class="[
                attributes.class,
                {
                    'uk-form-danger': authorizationRequired
                }
            ]"
        >
            <template v-if="selectedAuth && driver">
                <!-- icon -->
                <img
                    v-if="driver.icon"
                    :alt="driver.title"
                    :src="driver.icon"
                    uk-svg
                    width="20"
                    height="20"
                />
                <span v-else uk-icon="lock"></span>

                <!-- name -->
                <span class="uk-text-emphasis uk-margin-xsmall-left uk-text-baseline">{{
                    selectedAuth.name ||
                    selectedAuth.account ||
                    selectedAuth.username ||
                    driver.title ||
                    driver.name
                }}</span>

                <!-- meta -->
                <span class="uk-text-meta uk-margin-xsmall-left uk-text-baseline">{{
                    driver.name
                }}</span>
            </template>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import essentials from 'yooessentials';
import fields from '@yootheme/fields';
import { includes, isEmpty } from 'uikit-util';
import { AuthDropdown, AuthModal } from '../components';

export default {
    name: 'ConnectedAuthField',

    extends: fields.components.FieldText,

    computed: {
        selectedAuth() {
            return essentials.helpers.Auth.auths.find((auth) => auth.id === this.value);
        },

        authorizationRequired() {
            if (this.driver?.type !== 'oauth') {
                return false;
            }

            const auth = this.selectedAuth;
            const scopes = this.field.connections?.[auth?.driver];

            if (!auth) {
                return false;
            }

            return !essentials.helpers.Auth.validateScopes(auth, scopes);
        },

        empty() {
            return this.drivers.length === 1 && isEmpty(this.auths);
        },

        driver() {
            return essentials.helpers.Auth.drivers?.[this.selectedAuth?.driver];
        },

        drivers() {
            return Object.keys(this.field.connections ?? {})
                .map((driver) => essentials.helpers.Auth.drivers?.[driver]?.name)
                .filter(Boolean);
        },

        auths() {
            return essentials.helpers.Auth.auths.filter((auth) =>
                includes(this.drivers, auth.driver)
            );
        }
    },

    mounted() {
        // reset value if selected auth is not valid
        if (!this.selectedAuth) {
            this.value = '';
        }
    },

    methods: {
        isEmpty,

        add(driver) {
            driver = driver || this.drivers[0];

            return this.edit({ driver }).then((auth) => {
                if (auth?.id) {
                    this.value = auth.id;
                }
            });
        },

        async edit(auth = this.selectedAuth) {
            const driver = essentials.helpers.Auth.getDriver(auth.driver);
            const scopes = this.field.connections[auth.driver];

            return api.uikit.promptModal(AuthModal, { driver, scopes, values: auth });
        },

        select() {
            const drop = api.uikit.openDropdown(
                {
                    functional: true,
                    render: (h) =>
                        h(AuthDropdown, {
                            props: {
                                auths: this.auths,
                                drivers: essentials.helpers.Auth.drivers,
                                connections: this.field.connections
                            },
                            on: {
                                add: (driver) => {
                                    drop.hide();
                                    this.add(driver).then(() => {
                                        drop.$forceUpdate();
                                    });
                                },
                                edit: (auth) => {
                                    drop.hide();
                                    this.edit(auth).then(() => {
                                        drop.$forceUpdate();
                                    });
                                },
                                remove: (auth) => {
                                    const driver = essentials.helpers.Auth.drivers?.[auth.driver];
                                    const predeleteEndpoint = driver?.endpoints.predelete;

                                    essentials.helpers.Auth.remove(auth, predeleteEndpoint);

                                    drop.$forceUpdate();
                                    if (auth.id === this.value) {
                                        this.value = '';
                                    }
                                    if (isEmpty(this.auths)) {
                                        drop.hide();
                                    }
                                },
                                resolve: (auth) => {
                                    drop.hide();
                                    this.value = auth.id;
                                    drop.$forceUpdate();
                                }
                            }
                        })
                },
                {},
                this.$el,
                { classes: 'yo-dropdown' }
            );
        }
    }
};
</script>
