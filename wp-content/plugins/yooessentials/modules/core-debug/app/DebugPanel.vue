<template>
    <div>
        <fields-panel :panel="panel" :values="Config.values" />

        <div class="uk-margin-large">
            <button
                class="uk-button uk-button-default uk-width-1-1"
                type="button"
                @click="downloadDebugData"
            >
                {{ $t('Download Data') }}
            </button>

            <p class="uk-margin">
                {{
                    $t(
                        'Generates a ZIP with information about the site and theme configuration. Sensitive data might be included, be carefull who you share this with.'
                    )
                }}
            </p>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import essentials from 'yooessentials';

export default {
    props: {
        panel: {
            type: Object,
            default: () => ({})
        }
    },
    computed: {
        Config() {
            return essentials.helpers.Config;
        }
    },
    methods: {
        downloadDebugData() {
            const url = api.config.route + `&p=${encodeURIComponent('yooessentials/debug/data')}`;

            window.open(url);
        }
    }
};
</script>
