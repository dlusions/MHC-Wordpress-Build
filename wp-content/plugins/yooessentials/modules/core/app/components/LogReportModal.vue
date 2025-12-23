<template>
    <div>
        <div class="uk-modal-header">
            <h3 class="uk-margin-small">{{ $t(title) }}</h3>
            <span v-if="subtitle">{{ subtitle }}</span>
        </div>

        <div class="uk-modal-body">
            <ul class="uk-list uk-list-divider uk-list-large">
                <li v-for="(log, i) in $logs" :key="i">
                    <div v-if="log.message" class="uk-margin" v-html="log.message"></div>

                    <div v-if="log.error" class="uk-text-danger uk-margin">{{ log.error }}</div>

                    <ul v-if="log.args" class="uk-list uk-margin-remove">
                        <li v-for="(val, name) in log.args" :key="name">
                            <span class="uk-text-bold">{{ name }}</span>
                            <span class="uk-text-meta">{{ valueType(val) }}</span>
                            {{ val }}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-modal-close uk-button-primary">{{ $t('Close') }}</button>
        </div>
    </div>
</template>

<script>
import { isArray } from 'uikit-util';

export default {
    name: 'ReportModal',

    props: {
        title: {
            type: String,
            required: true
        },

        subtitle: {
            type: String
        },

        logs: {
            type: [Array, Object],
            required: true
        }
    },

    computed: {
        $logs() {
            return isArray(this.logs) ? this.logs : [this.logs];
        }
    },

    methods: {
        valueType(val) {
            if (val === null) {
                return 'null';
            }

            if (val === '') {
                return 'empty string';
            }

            if (isArray(val)) {
                return 'array';
            }

            return typeof val;
        }
    }
};
</script>
