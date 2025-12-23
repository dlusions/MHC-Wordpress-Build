<template>
    <div
        class="uk-card uk-card-body uk-card-hover yo-panel uk-padding-remove-horizontal uk-text-center"
        :class="{
            'uk-disabled uk-text-muted': disabled
        }"
    >
        <div class="uk-width-small uk-margin-auto">
            <span class="uk-inline">
                <img :alt="title" :src="icon" uk-svg width="40" height="40" />
                <span
                    v-if="badge"
                    class="uk-badge uk-background-secondary uk-position-top-right uk-position-z-index"
                    style="top: -12px; right: -12px"
                    >{{ badge }}</span
                >
            </span>
        </div>

        <div
            class="uk-margin-top uk-padding-small uk-padding-remove-vertical uk-margin-remove-bottom uk-text-wrap"
        >
            {{ title }}
        </div>

        <div
            v-if="description"
            class="uk-margin-small-top uk-padding-small uk-padding-remove-vertical uk-margin-remove-bottom uk-text-wrap uk-text-meta"
        >
            {{ description }}
        </div>

        <a href class="uk-position-cover uk-position-z-index" @click.prevent="$emit('resolve')"></a>
    </div>
</template>

<script>
export default {
    name: 'ResourcePickerItem',

    props: {
        title: String,
        icon: String,
        description: String,
        disabled: Boolean,
        badge: [String, Number]
    }
};
</script>
