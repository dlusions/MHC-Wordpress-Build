<template>
    <li class="uk-link" :class="{ 'uk-invisible-hover': !stack }">
        <a
            :uk-icon="icon"
            :title="$t(title)"
            :class="[
                'uk-preserve-width',
                {
                    'uk-icon-link': emit || callback
                }
            ]"
            uk-tooltip="delay: 500"
            @click.prevent="(emit && $emit('click'), callback && callback($event))"
        ></a>
    </li>
</template>

<script>
export default {
    name: 'ResourceListItemAction',

    props: {
        title: String,

        icon: String,

        stack: Boolean,

        emit: String,

        callback: String
    }
};
</script>
