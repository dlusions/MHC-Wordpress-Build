<template>
    <li v-if="divider" class="uk-nav-divider"></li>
    <!-- <li class="uk-visible-toggle" :class="{ 'uk-active': active, 'uk-parent': children.length }">
        <a class="uk-display-block" @click.prevent="children.length || $emit('resolve', value)">
            <div class="uk-text-nowrap uk-text-truncate">
                {{ text }}
                <span v-if="children.length" uk-nav-parent-icon class="uk-margin-small-left"></span>
            </div>
            <div v-if="meta" class="uk-nav-subtitle uk-text-muted uk-text-truncate">
                <span v-for="m in metaList" :key="m.value" class="uk-margin-small-right">
                    <b v-if="m.text">{{ m.text }}</b>
                    <span>{{ m.value }}</span>
                </span>
            </div>
        </a>
    </li> -->
</template>

<script>
export default {
    name: 'DropdownNavItem',

    props: {
        divider: {
            type: Boolean,
            required: false
        }
    }
};
</script>
