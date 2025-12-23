<template>
    <ModalForm ref="form" :panel="driver" :values="values" :prerequest="false" @resolve="resolve" />
</template>

<script>
import essentials from 'yooessentials';
import { set, unique } from 'yooessentials-util';
import { ModalForm } from 'yooessentials-components';

export default {
    name: 'AuthModal',

    components: {
        ModalForm
    },

    provide() {
        return {
            AuthModal: this
        };
    },

    props: {
        driver: {
            type: Object
        },
        scopes: {
            type: Array
        },
        values: {
            type: Object
        }
    },

    events: {
        'yooessentials-prerequest'(e) {
            e.params[0]['auth'] = this.$refs.form.draft;
            e.params[0]['requiredScopes'] = this.scopes;
        }
    },

    methods: {
        updateValues(values) {
            for (const key in values) {
                let value = values[key];
                set(this.values, key, value);
            }
        },

        connect() {
            const scopes = this.$refs.form.draft.scopes;

            return essentials.helpers.Auth.oauth(this.driver.name, this.scopes)
                .then((oauth) => {
                    oauth = {
                        id: oauth.id,
                        driver: oauth.driver,
                        scopes: oauth.scopes,
                        accessToken: oauth.accessToken,
                        refreshToken: oauth.refreshToken,
                        expiresAt: oauth.expiresAt
                    };

                    for (const prop in oauth) {
                        let value = oauth[prop];

                        // merge scopes
                        if (prop === 'scopes') {
                            value = unique([...(scopes ?? []), ...value]);
                        }

                        set(this.$refs.form.draft, prop, value);
                    }

                    // auto save the auth giving some time to the ui
                    setTimeout(() => {
                        this.$refs.form.loading = false;
                        this.$refs.form.save();
                    }, 500);
                })
                .catch(() => {});
        },

        resolve(values) {
            const auth = essentials.helpers.Auth.save(values);

            this.updateValues(auth);
            this.$emit('resolve', auth);
        }
    }
};
</script>
