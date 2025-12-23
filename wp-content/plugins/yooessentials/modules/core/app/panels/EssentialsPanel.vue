<template>
    <div>
        <ul class="uk-nav uk-nav-default yo-sidebar-marginless">
            <li v-for="item in items" :key="item.name">
                <a href @click.prevent="$trigger('openPanel', item.name)">{{
                    $t(item.title || item.name)
                }}</a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'EssentialsPanel',

    inject: ['Sidebar'],

    props: {
        panel: Object
    },

    computed: {
        items() {
            const items = Object.keys(this.panel?.items ?? {});

            return items
                .map((name) => ({
                    name,
                    title: this.panel.items[name],
                    priority: this.Sidebar?.panels?.[name]?.priority
                }))
                .sort((a, b) => (a.priority ?? 0) - (b.priority ?? 0));
        }
    }
};
</script>
