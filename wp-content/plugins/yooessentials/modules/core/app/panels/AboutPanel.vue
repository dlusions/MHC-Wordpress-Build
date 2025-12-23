<script>
import api from '@yootheme/api';
import essentials from 'yooessentials';
import ChangelogModal from './ChangelogModal.vue';

export default {
    name: 'AboutPanel',
    props: {
        panel: {
            type: Object
        }
    },
    computed: {
        version() {
            return essentials.version;
        }
    },
    methods: {
        openChangelog() {
            api.uikit.openModal(ChangelogModal);
        }
    }
};
</script>

<template>
    <div>
        <p>
            Essential Addons for YOOtheme Pro<br />
            v{{ version || '0.0.0' }} by
            <a href="https://zoolanders.com/essentials-for-yootheme-pro" target="_blank"
                >zoolanders.com</a
            >.
        </p>

        <button
            class="uk-button uk-button-default uk-width-1-1 uk-text-nowrap uk-margin-top"
            type="button"
            @click="openChangelog"
        >
            {{ $t('Changelog') }}
        </button>
    </div>
</template>
