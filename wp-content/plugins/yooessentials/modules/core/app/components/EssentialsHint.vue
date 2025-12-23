<template>
    <a
        class="uk-icon-link"
        uk-icon="ye--hint"
        :title="$t('Powered by Essentials')"
        uk-tooltip="delay: 500"
        @click.prevent="openModal"
    ></a>
</template>

<script>
import api from '@yootheme/api';
import { addClass, on, once, pointerDown } from 'uikit-util';
import EssentialsHintModal from './EssentialsHintModal.vue';

export default {
    name: 'EssentialsHint',

    props: {
        addon: {
            type: String,
            required: true
        }
    },

    methods: {
        openModal() {
            const title = {
                dynamic: 'Advanced Dynamic Workflow'
            };

            const modal = api.uikit.openModal(EssentialsHintModal, {
                title: title[this.addon] || 'Essentials',
                addon: this.addon
            });

            addClass(modal.$el, 'ye-hint-modal');
            addClass(modal.modal.panel, 'uk-margin-auto-vertical');

            once(modal.$el, 'shown', () => {
                addClass(modal.$el, 'ye-hint-modal-shown');
            });

            on(modal.$el, pointerDown, (e) => {
                e.preventDefault();
                e.stopPropagation();

                modal.hide();
            });

            modal.show();
        }
    }
};
</script>
