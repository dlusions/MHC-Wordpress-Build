<template>
    <form @submit.prevent="$emit('resolve', { node, library: library || chosenLibrary })">
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">
                {{
                    edit ? $t('Rename %type%', { type: title }) : $t('Save %type%', { type: title })
                }}
            </h2>
        </div>

        <div class="uk-modal-body uk-form-stacked">
            <div class="uk-margin">
                <label for="form-element-save-name" class="uk-form-label">{{ $t('Name') }}</label>
                <input
                    id="form-element-save-name"
                    v-model="node.name"
                    :placeholder="type.title"
                    class="uk-input"
                    type="text"
                    required
                    autofocus
                />
            </div>

            <div v-if="!library">
                <label for="form-element-save-library" class="uk-form-label">{{
                    $t('Library')
                }}</label>
                <select v-model="chosenLibrary" class="uk-select">
                    <option value="my">
                        {{ $t(node.type === 'section' ? 'My Layouts' : 'My Presets') }}
                    </option>
                    <option v-for="lib in libraries" :key="lib.id" :value="lib.id">
                        {{ $t(lib.name) }}
                    </option>
                </select>
            </div>

            <p v-show="!edit && exists" class="uk-text-muted uk-margin-small">
                {{
                    $t(
                        '"%name%" already exists in the library, it will be overwritten when saving.',
                        { name: node.name },
                    )
                }}
            </p>
        </div>

        <div class="uk-modal-footer uk-text-right">
            <button
                class="uk-button uk-button-text uk-modal-close uk-margin-small-right"
                type="button"
            >
                {{ $t('Cancel') }}
            </button>
            <button class="uk-button uk-button-primary">{{ $t('Save') }}</button>
        </div>
    </form>
</template>

<script>
import essentials from 'yooessentials';

export default {
    name: 'NodeModal',

    props: {
        edit: {
            type: Boolean,
            default: false,
        },

        node: {
            type: Object,
            required: true,
        },

        builder: {
            type: Object,
            required: true,
        },

        libraries: {
            type: Array,
            required: true,
        },

        library: {
            type: String,
            default: '',
        },
    },

    data: () => ({
        exists: false,
        chosenLibrary: 'my',
    }),

    computed: {
        type() {
            return essentials.yoo.Builder.type(this.node);
        },

        title() {
            return this.type.element ? 'Element Preset' : this.type.title || this.node.type;
        },
    },

    created() {
        if (!this.edit) {
            this.$set(this.node, 'name', '');
        }
    },
};
</script>
