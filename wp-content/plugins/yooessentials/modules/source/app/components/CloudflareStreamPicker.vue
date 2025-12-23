<template>
    <div>
        <div class="uk-modal-body">
            <CloudflareStreamFinder ref="finder" :items="items" :selected.sync="selected" />
        </div>

        <div class="uk-modal-footer uk-flex uk-flex-middle uk-flex-between">
            <div class="uk-text-danger">{{ message }}</div>
            <div class="uk-text-nowrap">
                <button
                    class="uk-button uk-button-text uk-modal-close uk-margin-right"
                    type="button"
                >
                    {{ $t('Cancel') }}
                </button>
                <button
                    :disabled="selected.length !== 1"
                    class="uk-button uk-button-primary"
                    type="button"
                    @click.prevent="select"
                >
                    {{ $t('Select') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import CloudflareStreamFinder from './CloudflareStreamFinder/index.vue';
import { CloudflareStreamModel } from '../models';

export default {
    name: 'CloudflareStreamPicker',

    components: {
        CloudflareStreamFinder
    },

    models: {
        Stream: CloudflareStreamModel
    },

    props: {
        sourceId: {
            type: String,
            required: true
        }
    },

    data: () => ({
        items: [],
        selected: [],
        message: ''
    }),

    created() {
        this.Stream.fetchStreams({ sourceId: this.sourceId })
            .then((streams) => {
                this.items = streams.map(({ uid, ...stream }) => ({ id: uid, ...stream }));
            })
            .catch((res) => (this.message = res.statusText));
    },

    methods: {
        select() {
            this.$emit('resolve', this.Stream.streams[this.selected[0]]);
        }
    }
};
</script>
