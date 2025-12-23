/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export default function (SourceFieldArgs) {
    return {
        name: 'SourceFieldArgsOverride',
        inject: ['Builder'],
        props: {
            field: Object,
            values: Object
        },
        render(h) {
            // support for prop source
            this.node = this.values?.query ? { source: this.values } : this.$parent.node;

            return;

            return h(SourceFieldArgs);
        }
    };
}
