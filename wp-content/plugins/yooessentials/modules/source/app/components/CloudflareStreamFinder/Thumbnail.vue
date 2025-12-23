<template>
    <ul
        class="uk-grid-medium uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl"
        uk-grid
    >
        <li v-for="item in Finder.items" :key="item.id">
            <div class="uk-card uk-card-default uk-card-small" @click="Finder.toggleSelect(item)">
                <div class="uk-card-media-top uk-position-relative">
                    <a
                        class="uk-background-contain uk-position-cover"
                        :data-src="$url(item.thumbnail)"
                        uk-img
                        :href="item.preview"
                        target="_blank"
                    ></a>
                    <canvas width="1920" height="1080"></canvas>
                </div>
                <div class="uk-card-body uk-text-center uk-text-truncate uk-text-nowrap">
                    <input :checked="Finder.isSelected(item)" class="uk-checkbox" type="checkbox" />
                    {{ item.meta.name }}
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
export default {
    inject: ['Finder']
};
</script>
