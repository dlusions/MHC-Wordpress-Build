<template>
    <div>
        <fields-panel :panel="panel" :values="Config.values" />

        <h3 class="yo-sidebar-subheading">{{ $t('Settings') }}</h3>

        <div>
            <button
                class="uk-button uk-button-default uk-width-1-1"
                type="button"
                @click="exportConfig"
            >
                {{ $t('Export Settings') }}
            </button>

            <div class="uk-margin-small-top uk-display-block" uk-form-custom>
                <input
                    ref="input"
                    accept="application/json"
                    type="file"
                    name="files[]"
                    @change="uploadFile"
                />
                <button class="uk-button uk-button-default uk-width-1-1" type="button">
                    {{ $t('Import Settings') }}
                </button>
            </div>
        </div>

        <p>
            {{
                $t(
                    'Export Essentials settings and/or import them deleting any previous configuration.'
                )
            }}
        </p>

        <template v-for="(panel, name) in panel.panels">
            <h3 class="yo-sidebar-subheading">{{ $t(panel.title) }}</h3>
            <component :is="name" :panel="panel"></component>
        </template>
    </div>
</template>

<script>
import api from '@yootheme/api';
import essentials from 'yooessentials';
import { download, readFile } from '../util';

export default {
    name: 'AdvancedPanel',

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
        importConfig(config) {
            this.Config.import(config);
        },

        async exportConfig() {
            const { hostname } = window.location;
            const config = await this.Config.export();

            download(`yooessentials-${hostname}.json`, JSON.stringify(config, null, 2) + '\n');
        },

        uploadFile({ currentTarget, dataTransfer }) {
            const [file] = currentTarget.files || dataTransfer?.files || [];

            if (file) {
                readFile(file)
                    .then(({ target }) => this.importConfig(JSON.parse(target.result)))
                    .catch(() => api.uikit.notify(`Error loading '${file.name}'`, 'danger'));
            }
        }
    }
};
</script>
