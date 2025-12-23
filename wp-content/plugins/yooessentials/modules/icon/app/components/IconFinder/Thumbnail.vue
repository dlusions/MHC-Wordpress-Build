<template>
    <div class="uk-grid-collapse uk-child-width-auto" uk-grid>
        <div v-for="icon in icons" :key="icon.key">
            <div
                class="uk-card uk-card-small uk-card-body uk-card-hover yo-panel uk-inline"
                style="width: 80px; height: 80px"
            >
                <span :icon="icon.key" uk-icon width="80" height="80"></span>
                <a
                    :title="icon.key"
                    class="uk-position-cover"
                    href=""
                    uk-tooltip="delay: 500; position: top"
                    @click.prevent="$emit('resolve', icon.key)"
                ></a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        icons: {
            type: Array,
            required: true
        }
    }
};
</script>
