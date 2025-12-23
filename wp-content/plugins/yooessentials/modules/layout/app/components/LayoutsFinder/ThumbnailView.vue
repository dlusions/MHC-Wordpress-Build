<template>
    <ul
        class="uk-grid-medium uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl"
        uk-grid
    >
        <li v-for="node in Finder.items" :key="node.id">
            <div
                class="uk-card uk-card-default uk-card-small"
                @click="Finder.$emit('select', node)"
            >
                <div class="uk-card-media-top uk-position-relative">
                    <a
                        class="uk-background-contain uk-position-cover"
                        :data-src="$url(node.thumbnail)"
                        uk-img
                        :href="node.preview"
                        target="_blank"
                    ></a>
                    <canvas width="1920" height="1080"></canvas>
                </div>
                <div class="uk-card-body uk-text-center uk-text-truncate uk-text-nowrap">
                    {{ node.name }}
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
export default {
    name: 'ThumbmnailView',

    inject: ['Finder']
};
</script>
