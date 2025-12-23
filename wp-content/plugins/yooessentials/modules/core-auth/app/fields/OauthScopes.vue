<template>
    <div v-if="!scopes.length">
        {{ $t('None') }}
    </div>

    <ul v-else class="uk-list">
        <li v-for="scope in scopes" :key="scope.name" class="uk-flex uk-flex-between">
            <template v-if="isOauth">
                <span
                    :class="{
                        'uk-text-muted': !validates(scope.name)
                    }"
                    >{{ scope.title }}</span
                >
                <span
                    :key="scope.name"
                    :uk-icon="validates(scope.name) ? 'check' : 'close'"
                    class="uk-flex-none"
                    :class="{
                        'uk-text-muted': !validates(scope.name)
                    }"
                ></span>
            </template>
            <span v-else>{{ scope.title }}</span>
        </li>
    </ul>
</template>

<script>
import fields from '@yootheme/fields';
import { includes } from 'uikit-util';
import { unique } from 'yooessentials-util';

export default {
    name: 'oAuthScopes',

    extends: fields.components.FieldText,

    inject: ['AuthModal'],

    computed: {
        scopes() {
            const scopes = [...(this.values?.scopes ?? []), ...(this.AuthModal?.scopes || [])];

            return unique(scopes)
                .filter((scope) => this.AuthModal.driver?.scopes?.[scope])
                .map((scope) => ({
                    name: scope,
                    title: this.AuthModal.driver.scopes[scope]
                }));
        },

        isOauth() {
            return this.AuthModal.driver?.type === 'oauth';
        }
    },

    methods: {
        validates(scope) {
            return includes(this.values.scopes, scope);
        }
    }
};
</script>
