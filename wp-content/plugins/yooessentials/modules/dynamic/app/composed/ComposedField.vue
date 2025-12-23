<template>
    <div
        class="ye-composed-field uk-position-relative"
        :class="{
            'uk-input': !multiline,
            'uk-textarea uk-padding-remove': multiline
        }"
    >
        <ComposedEditor
            ref="editor"
            v-model="source.composed.value"
            class="uk-flex-1"
            :field="field"
            :sources="source.composed.sources"
            :conditions="source.composed.conditions"
            :multiline="multiline"
        />

        <ul
            :class="`uk-iconnav uk-flex-middle uk-position-${multiline ? 'bottom' : 'center'}-right uk-position-small`"
        >
            <li v-if="isExpandable">
                <a
                    :uk-icon="multiline ? 'shrink' : 'expand'"
                    ratio="0.9"
                    class="uk-icon-link"
                    :uk-tooltip="`delay: 500; title: ${multiline ? $t('Shrink') : $t('Expand')}`"
                    @click="toggleMultiline"
                ></a>
            </li>
            <li>
                <a
                    uk-icon="trash"
                    class="uk-icon-link"
                    uk-tooltip="delay: 500"
                    :title="$t('Remove')"
                    @click.prevent.stop="$emit('remove')"
                ></a>
            </li>
        </ul>
    </div>
</template>

<script>
import Vue from 'vue';
import UIkit from 'uikit';
import { isObject, isArray } from 'uikit-util';
import ComposedEditor from './ComposedEditor.vue';

export default {
    name: 'ComposedField',

    components: {
        ComposedEditor
    },

    inject: ['$node'],

    props: {
        field: {
            type: Object,
            required: true
        },
        source: {
            type: Object,
            required: true
        }
    },

    data: () => ({
        multiline: false
    }),

    computed: {
        isEditorType() {
            return this.field.type === 'editor';
        },

        isExpandable() {
            return !this.isEditorType && !this.source.composed?.value?.includes('\n');
        }
    },

    watch: {
        'source.composed.value': {
            immediate: true,
            handler(v) {
                if (this.isEditorType || v.includes('\n')) {
                    this.multiline = true;
                }
            }
        }
    },

    created() {
        const isValid = (obj) => isObject(obj) && !isArray(obj);

        if (!isValid(this.source.composed.sources)) {
            Vue.set(this.source.composed, 'sources', {});
        }

        if (!isValid(this.source.composed.conditions)) {
            Vue.set(this.source.composed, 'conditions', {});
        }
    },

    mounted() {
        if (this.source.composed?.new) {
            Vue.delete(this.source.composed, 'new');
            this.$refs.editor.cm.focus();
        }
    },

    methods: {
        toggleMultiline(e) {
            if (this.$refs?.editor?.frozen) {
                return;
            }

            const target = e.target.nodeName !== 'a' ? e.srcElement.closest('a') : e.target;

            UIkit.tooltip(target).hide();
            this.multiline = !this.multiline;
        }
    }
};
</script>
