<template>
    <div v-if="isEmpty(value)">
        <button
            class="uk-button uk-button-default uk-margin-small-top uk-width-1-1"
            type="button"
            @click.prevent="add"
        >
            {{ $t(field.txtAdd || 'Add') }}
        </button>
    </div>

    <ul v-else class="uk-list uk-margin-medium-top ye-repeatable-compact">
        <li
            v-for="(v, i) in value"
            :key="i"
            class="uk-visible-toggle uk-position-relative uk-margin-small-top"
            tabindex="-1"
            @copy="copy(i)"
            @move="(pos) => move(i, pos)"
            @remove="remove(i)"
        >
            <fields-panel :panel="{ fields: processFields(field.fields, i) }" :values="v" />

            <a
                href=""
                uk-tooltip="delay: 1000; pos: left"
                :style="{ top: i === 0 ? '25px' : 0 }"
                class="ye-repeatable-compact-delete yo-builder-icon-delete uk-invisible-hover"
                :title="$t('Remove item')"
                @click.prevent="remove(i)"
            ></a>
            <a
                href=""
                uk-tooltip="delay: 1000; pos: left"
                class="ye-repeatable-compact-add yo-builder-icon-add-right uk-invisible-hover"
                :title="$t('Add item')"
                @click.prevent="add(i + 1)"
            ></a>
        </li>
    </ul>
</template>

<script>
import { deepCopy } from '../util';
import RepeatableMultiField from './RepeatableMulti.vue';

export default {
    name: 'RepeatableCompact',

    extends: RepeatableMultiField,

    methods: {
        processFields(fields, index) {
            fields = deepCopy(fields);

            Object.keys(fields).forEach((field) => {
                if (index !== 0) {
                    delete field.label;
                }

                if (field.type === 'grid') {
                    field.fields = this.processFields(field.fields, index);
                }

                return field;
            });

            return fields;
        }
    }
};
</script>
