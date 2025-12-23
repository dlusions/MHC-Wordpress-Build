<template>
    <div v-if="isEmpty(adapters)" class="uk-modal-body uk-text-danger">
        No Storage Adapters Found
    </div>

    <ModalForm
        v-else-if="values.adapter"
        ref="form"
        :values="values"
        :panel="adapter"
        :prerequest="false"
        @resolve="save"
    />

    <ResourcePicker
        v-else
        :items="Object.values(adapters)"
        @resolve="({ name }) => setAdapter(name)"
    />
</template>

<script>
import { $, isEmpty } from 'uikit-util';
import { ModalForm, ResourcePicker } from 'yooessentials-components';

export default {
    name: 'StorageModal',

    components: { ModalForm, ResourcePicker },

    props: {
        adapters: {
            type: Object,
            required: true,
        },

        values: {
            type: Object,
            required: true,
        },
    },

    computed: {
        adapter() {
            return this.adapters?.[this.values.adapter];
        },
    },

    watch: {
        'values.adapter': function () {
            this.$nextTick(() => {
                const focus = $('[autofocus]', this.$el);
                focus && focus.focus();
            });
        },
    },

    methods: {
        isEmpty,

        save(values) {
            this.$emit('resolve', values);
        },

        setAdapter(adapter) {
            this.$set(this.values, 'adapter', adapter);
        },
    },

    events: {
        'yooessentials-prerequest'(e) {
            e.params[0]['storage'] = this.$refs.form.draft;
        },
    },
};
</script>
