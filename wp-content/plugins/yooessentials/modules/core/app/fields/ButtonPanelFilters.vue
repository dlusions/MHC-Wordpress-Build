<template>
    <div
        v-bind="attributes"
        :class="{
            'ye-open-panel-margin': rules.length,
            'yo-button-medium': attributes.class === 'yo-form-medium'
        }"
        class="uk-input ye-open-panel uk-inline uk-width-1-1 uk-flex uk-flex-middle"
        @click.prevent="open"
    >
        <span v-if="rules.length" class="uk-text-truncate uk-margin-large-right uk-disabled">
            {{ $t('Conditioned') }} -
            <span v-if="activeRules.length">
                {{ activeRules.length }} <span class="uk-text-meta">{{ $t('Active') }}</span>
            </span>
            <span v-if="inactiveRules.length">
                {{ inactiveRules.length }} <span class="uk-text-meta">{{ $t('Inactive') }}</span>
            </span>
        </span>

        <template v-else>
            {{ $t('None') }}
        </template>

        <span v-if="rules.length" class="uk-position-center-right uk-position-small">
            <ul class="uk-iconnav uk-flex-nowrap">
                <li>
                    <a
                        :title="$t('Remove')"
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="trash"
                        uk-tooltip="delay: 500"
                        @click.prevent.stop="empty"
                    ></a>
                </li>
            </ul>
        </span>
    </div>
</template>

<script>
import ButtonPanel from './ButtonPanel.vue';

export default {
    name: 'ButtonPanelFilters',

    extends: ButtonPanel,

    inject: ['$node'],

    computed: {
        rules() {
            return (this.$node?.source?.query?.arguments?.filters || []).filter(
                (rule) =>
                    (rule?.props?.value ||
                        rule?.source?.props?.value ||
                        rule?.source_extended?.props?.value) &&
                    rule?.props?.field &&
                    rule?.props?.operator
            );
        },

        activeRules() {
            return this.rules.filter((rules) => rules?.props?.status !== 'disabled');
        },

        inactiveRules() {
            return this.rules.filter((rules) => rules?.props?.status === 'disabled');
        }
    },

    methods: {
        empty() {
            this.$node.source.query.arguments.filters = [];
        }
    }
};
</script>
