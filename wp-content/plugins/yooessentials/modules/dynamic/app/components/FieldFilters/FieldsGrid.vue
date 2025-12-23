<template functional>
    <div
        :class="
            props.field.attrs?.class === 'yo-form-medium'
                ? 'uk-grid-small uk-margin-remove-bottom'
                : `uk-grid-${props.field.gap || 'medium'}`
        "
        class="yo-sidebar-grid uk-grid uk-flex-nowrap"
    >
        <div
            v-for="(field, i) in parent.prepare(props.field.fields)"
            v-show="parent.evaluate(field.show)"
            :key="field.name"
            :class="$options.widthClass(props.field.width, i)"
        >
            <template v-if="field.buttons">
                <div class="uk-flex uk-flex-middle uk-flex-right">
                    <div v-if="field.label" class="uk-width-expand">
                        <h3 class="yo-sidebar-subheading uk-margin-remove">
                            {{ parent.$t(field.label) }}
                        </h3>
                    </div>
                    <div class="uk-width-auto">
                        <ul class="uk-subnav uk-margin-remove">
                            <li
                                v-for="{ label, action, show } in field.buttons"
                                v-show="parent.evaluate(show)"
                                :key="action"
                            >
                                <button
                                    :disabled="field.enable && !parent.evaluate(field.enable)"
                                    type="button"
                                    class="uk-button uk-button-link"
                                    @click="parent.$trigger(action, [field, $event])"
                                >
                                    {{ label }}
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </template>

            <h3 v-else-if="field.label" class="yo-sidebar-subheading">
                {{ parent.$t(field.label) }}
            </h3>

            <template v-if="field.type !== 'description'">
                <div
                    v-if="!['radio', 'checkbox', 'grid', 'parallax-stops'].includes(field.type)"
                    class="uk-margin-small"
                >
                    <component
                        :is="field.component"
                        :field="$options.prepareField(props.field, field)"
                        :values="props.values"
                        @change="parent.change"
                    />
                </div>
                <component
                    :is="field.component"
                    v-else
                    :field="$options.prepareField(props.field, field)"
                    :values="props.values"
                    @change="parent.change"
                />
            </template>

            <p
                v-if="field.description"
                class="uk-text-muted uk-margin-small"
                v-html="parent.$t(field.description)"
            ></p>
        </div>
    </div>
</template>

<script>
import merge from 'lodash-es/merge';

export default {
    name: 'FieldsGrid',

    widthClass(widthClass, i) {
        const classes = widthClass?.split(',') || [];
        const cls = classes.at(i in classes ? i : -1)?.trim();
        return cls ? `uk-width-${cls}` : '';
    },

    prepareField(parent, field) {
        if (parent.attrs?.class === 'yo-form-medium') {
            field = merge({}, field, { attrs: { class: parent.attrs.class } });
        }

        return field;
    }
};
</script>
