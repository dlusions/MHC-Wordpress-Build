<template>
    <div>
        <div class="yo-dropdown-body uk-overflow-auto uk-height-max-medium">
            <ul class="uk-nav uk-dropdown-nav">
                <li
                    v-for="field in fields"
                    :key="field.props.control_name"
                    @click.prevent="$emit('resolve', field)"
                >
                    <a href>
                        <!-- <img class="uk-svg uk-margin-small-right" :alt="field.type.title" :src="field.type.iconSmall" width="20" height="20" uk-svg> -->
                        <span class="uk-text-emphasis uk-text-middle">{{
                            field.props.control_name || field.type.title || field.type.name
                        }}</span>
                        <span class="uk-text-middle uk-text-meta">{{ field.type.title }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        fields: {
            type: Array,
            required: true
        }
    }
};
</script>
