<script>
export default {
    props: {
        node: Object,
        child: Boolean,
        parent: Boolean,
        multiple: Boolean,
        error: [Boolean, String],
        tooltipDirection: {
            type: String,
            default: 'bottom'
        }
    },
    computed: {
        icon() {
            let icon = 'yo-builder-icon-dynamic';

            if (this.parent) {
                icon += '-p';
            }

            if (this.multiple) {
                icon += '-n';
            }

            if (this.error) {
                icon += '-error';
            }

            return icon;
        },
        title() {
            const errors = {
                'empty-props': this.$t('No Field Mapped'),
                'invalid-field': this.$t('Invalid Field Mapped')
            };

            const title = this.error
                ? (errors[this.error] ?? this.$t('Invalid Source'))
                : this.multiple
                  ? this.parent
                      ? this.$t('Dynamic Multiplication (Parent Source)')
                      : this.$t('Dynamic Multiplication')
                  : this.parent
                    ? this.$t('Dynamic Content (Parent Source)')
                    : this.$t('Dynamic Content');

            return this.child ? this.$t('Contains %title%', { title }) : title;
        }
    }
};
</script>

<template>
    <span :class="icon" :title="title" :uk-tooltip="`delay: 1000; pos: ${tooltipDirection}`"></span>
</template>
