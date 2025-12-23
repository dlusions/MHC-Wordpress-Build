<template>
    <div v-if="isEmpty(value)" class="uk-margin">
        <div>{{ $t('No rules yet.') }}</div>

        <button
            class="uk-button uk-button-default uk-margin-top"
            type="button"
            @click.prevent="add"
        >
            {{ $t('Add Rule') }}
        </button>
    </div>

    <div v-else class="uk-margin uk-visible-toggle">
        <ul
            v-sortable="{ group: 'dataset-items', enabled: isSortable }"
            :class="[
                'uk-nav uk-nav-default yo-sidebar-marginless yo-nav-sortable uk-margin uk-margin-remove-bottom',
                { 'yo-nav-iconnav': !attributes.disabled }
            ]"
            cls-custom="yo-nav-sortable-drag"
        >
            <ResourceListItem
                v-for="{ item, disabled, type, ...props } in items"
                :key="item.id"
                v-bind="props"
                :disabled="Boolean(attributes.disabled || disabled)"
                :editable="Boolean(type)"
                @edit="edit(item)"
                @copy="copy(item)"
                @delete="remove(item)"
            />
        </ul>

        <hr class="uk-margin-remove" />

        <div class="uk-flex uk-flex-between uk-margin-small-top">
            <div>
                <button
                    type="button"
                    class="uk-button uk-button-small uk-button-default"
                    @click.prevent="add"
                >
                    {{ $t('Add Rule') }}
                </button>
            </div>

            <div v-if="logs">
                <span :title="$t('Evaluation')" uk-tooltip="delay: 500">
                    <img :uk-svg="`${$assets}/report-${logs.result ? 'check' : 'ban'}.svg`" />
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import essentials from 'yooessentials';
import { LogReportModal } from 'yooessentials-components';
import { FieldYooessentialsDatasetMulti } from 'yooessentials-fields';

export default {
    name: 'FieldConditionRules',

    idPrefix: 'ci_',

    extends: FieldYooessentialsDatasetMulti,

    inject: {
        $node: {}
    },

    computed: {
        logs() {
            // override
            return {};
        },

        items() {
            return this.value.map((item, i) => {
                const type = this.options[item.type];
                const props = item?.props || {};
                const title = props.name || 'Rule';
                const meta = `{${++i}} ${type?.title}`;

                // get all node logs for the rule
                const logs = this.logs?.entries?.find(({ id }) => id === item.id);

                return {
                    item,
                    title,
                    meta,
                    type,
                    error: !type ? `Unknown Rule Type: ${item.type}` : '',
                    disabled: !type || props.status === 'disabled',
                    icon: type?.icon,
                    description: type?.description,
                    actions: [
                        this.isCopyable && type ? 'copy' : null,
                        this.isDeletale !== false ? 'delete' : null,
                        logs && {
                            title: this.$t('Evaluation'),
                            icon: `ye--condition-report-${logs.result ? 'check' : 'ban'}`,
                            stack: true,
                            callback: () => {
                                this.showRuleLogs(item, logs);
                            }
                        }
                    ].filter(Boolean)
                };
            });
        },

        options() {
            const rules = essentials.customizer?.condition_rules || [];

            return Object.entries(rules).reduce((acc, [name, rule]) => {
                acc[name] = {
                    ...rule,
                    panel: this.getPanel({ ...rule.panel })
                };
                return acc;
            }, {});
        }
    },

    created() {
        this.$assets = `${essentials.customizer.assets}/core-condition/assets`;
    },

    methods: {
        showRuleLogs(rule) {
            const index = this.value.findIndex(({ id }) => id === rule.id) + 1;
            const rules = essentials.customizer?.condition_rules || [];
            const ruleType = rules[rule.type];

            const logs = this.logs.entries
                .filter(({ id }) => id === rule.id)
                .map((log) => ({
                    ...log,
                    args: log.props,
                    message: `Evaluated as <span class="uk-text-bold">${log.result}</span> with the following arguments:`
                }));

            api.uikit.openModal(LogReportModal, {
                title: this.$t('Evaluation'),
                subtitle: `{${index}} ${ruleType.title}`,
                logs
            });
        }
    }
};
</script>
