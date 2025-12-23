<template>
    <form @submit.prevent="save">
        <slot name="header">
            <div class="uk-modal-header uk-flex uk-flex-middle uk-flex-between">
                <div
                    v-if="panel.title || panel.description"
                    class="uk-margin-large-right uk-flex-1"
                >
                    <h3 v-if="panel.title" class="uk-margin-remove">{{ $t(panel.title) }}</h3>
                    <div v-if="panel.description" class="uk-margin-small-top">
                        {{ $t(panel.description) }}
                        <span v-if="panel.docs">
                            Review the
                            <a :href="panel.docs" target="_blank">Documentation Guide</a> for tips
                            and best practices.
                        </span>
                    </div>
                </div>
                <div v-if="panel.icon" class="uk-flex-middle uk-text-lead">
                    <img :src="panel.icon" uk-svg width="60" height="60" />
                </div>
            </div>
        </slot>

        <div class="uk-modal-body">
            <slot name="prefields"></slot>
            <slot name="panel" :draft="draft">
                <fields-panel :panel="panel" :values="draft" />
            </slot>
        </div>

        <div class="uk-modal-footer uk-flex uk-flex-middle uk-flex-between">
            <span v-if="loading" uk-spinner></span>
            <div v-else class="uk-text-danger uk-overflow-auto">{{ error }}</div>
            <div class="uk-text-nowrap" :class="{ 'uk-disabled': loading }">
                <button
                    class="uk-button uk-button-text uk-modal-close uk-margin-small-right"
                    type="button"
                >
                    {{ $t('Cancel') }}
                </button>
                <button :class="`uk-button uk-button-${error ? 'danger' : 'primary'}`">
                    {{ $t('Save') }}
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import http from '@yootheme/http';
import { isString } from 'uikit-util';
import { deepCopy } from '../util';

export default {
    name: 'ModalForm',

    provide() {
        return {
            ModalForm: this
        };
    },

    props: {
        panel: {
            type: Object,
            required: true
        },

        values: {
            type: Object,
            required: true
        },

        prerequest: {
            type: Boolean,
            default: true
        }
    },

    data: () => ({
        draft: {},
        error: '',
        loading: false
    }),

    computed: {
        dirty() {
            return JSON.stringify(this.values) !== JSON.stringify(this.draft);
        }
    },

    watch: {
        draft: {
            handler() {
                this.error = '';
            },
            deep: true
        }
    },

    events: {
        'yooessentials-prerequest'(e) {
            if (this.prerequest && !e.params?.[0]?.['form']) {
                e.params[0]['form'] = this.draft;
            }
        },

        'yooessentials-resolve-field-argument'(e, params) {
            const values = this.draft;
            const regex = new RegExp('#__{values.(.*)}');

            for (const key in params) {
                const param = params[key];
                const matched = isString(param) && param.match(regex);

                if (matched) {
                    params[key] = param.replace(regex, values[matched[1]] ?? '');
                }
            }
        }
    },

    created() {
        this.draft = deepCopy(this.values);
    },

    methods: {
        save() {
            return new Promise((resolve, reject) => {
                const endpoint = this.panel?.endpoints?.presave;

                if (!endpoint) {
                    resolve(this.draft);
                    return;
                }

                this.loading = true;

                return http(endpoint)
                    .post({})
                    .json((data) => {
                        resolve({ ...this.draft, ...(data || {}) });
                    })
                    .catch((e) => reject(e?.json));
            })
                .then((values) => {
                    this.loading = false;
                    this.$emit('resolve', values);
                })
                .catch((e) => {
                    this.loading = false;
                    this.error = e || 'Unknown Error';
                });
        }
    }
};
</script>
