<template>
    <form @submit.prevent="$emit('resolve', draft)">
        <div class="uk-modal-header uk-flex uk-flex-middle uk-flex-between">
            <h2 class="uk-modal-title uk-margin-remove-bottom">
                {{ $t(isEmpty(values) ? 'Save Query' : 'Edit Query') }}
            </h2>

            <div
                v-if="!isEmpty(values)"
                class="uk-grid-small uk-child-width-auto uk-flex-middle"
                uk-grid
            >
                <div>
                    <button
                        class="uk-button uk-button-default uk-modal-close"
                        type="button"
                        @click="$trigger('yeCopyGlobalQuery', [draft], true)"
                    >
                        {{ $t('Copy') }}
                    </button>
                </div>
                <div>
                    <button
                        v-confirm="$t('Are you sure?')"
                        class="uk-button uk-button-danger uk-modal-close"
                        type="button"
                        @click="$trigger('yeDeleteGlobalQuery', [draft], true)"
                    >
                        {{ $t('Delete') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="uk-modal-body">
            <BuilderPanel :node="draft" :builder="Builder" :panel="panel" :values="draft" />
        </div>

        <div class="uk-modal-footer uk-flex uk-flex-middle uk-flex-right">
            <button class="uk-button uk-button-text uk-modal-close uk-margin-right" type="button">
                {{ $t('Cancel') }}
            </button>
            <button class="uk-button uk-button-primary" :disabled="!validated">
                {{ $t('Save') }}
            </button>
        </div>
    </form>
</template>

<script>
import essentials from 'yooessentials';
import { deepCopy } from '@yooessentials/util';
import { isEmpty } from 'uikit-util';
import { BuilderPanel } from '@yooessentials/components';

export default {
    name: 'GlobalQueryModal',

    components: { BuilderPanel },

    inject: {
        Builder: {
            default() {
                return essentials.yoo.Builder;
            }
        }
    },

    props: {
        values: {
            type: Object,
            default: () => ({})
        }
    },

    data: () => ({
        draft: {},
        error: ''
    }),

    computed: {
        validated() {
            return this.draft.name && this.draft.description;
        },

        panel() {
            return {
                title: 'Source Query',
                fields: {
                    name: {
                        label: 'Name',
                        description: 'A name to identify this query.',
                        attrs: {
                            autofocus: true,
                            required: true
                        }
                    },
                    description: {
                        label: 'Description',
                        description: 'A description for this query.',
                        attrs: {
                            required: true
                        }
                    }
                }
            };
        }
    },

    created() {
        this.draft = deepCopy(this.values);

        // necessary as the builder is not inited
        essentials.yoo.Builder = fakeBuilder();
    },

    methods: {
        isEmpty
    }
};

function fakeBuilder() {
    return {
        isPage: () => false,
        path: (n) => [n],
        type: () => ({}),
        parent: () => null,
        exists: () => false
    };
}
</script>
