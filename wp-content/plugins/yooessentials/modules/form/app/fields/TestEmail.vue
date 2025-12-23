<template>
    <div>
        <button class="uk-button uk-button-default" type="button" @click="test">
            {{ $t('Test') }}
        </button>

        <span v-if="statusText" :class="{ 'uk-text-danger': status === 400 }">
            {{ statusText }}
        </span>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';

export default {
    extends: fields.components.FieldText,

    inject: ['Builder'],

    data: () => ({
        status: 200,
        statusText: '',
    }),

    computed: {
        formAreaNode() {
            let node = this.$parent.node;

            while (node && !node.props?.yooessentials_form?.state) {
                node = this.Builder.parent(node);
            }

            return node;
        },
    },

    methods: {
        test() {
            const formid = this.formAreaNode?.formid;
            const action = this.$parent.node.id;

            if (!formid) {
                this.statusText = this.$t('No form ID found');
                return;
            }

            this.statusText = '';

            api.http('yooessentials/form-send-test-email')
                .post({ formid, action })
                .json((response) => {
                    this.status = response.status;
                    this.statusText = this.$t('Email succesfully sent!');
                })
                .catch((e) => {
                    this.status = e.response.status;
                    this.statusText = e.response.statusText;
                });
        },
    },
};
</script>
