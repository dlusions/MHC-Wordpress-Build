<template>
    <div class="ye-dropdown ye-dynamic-dropdown">
        <QueryFieldsList
            v-if="!source.queryPath"
            :node="$node"
            :options="options"
            :max-height="maxHeight"
            @resolve="setQuery"
        />
        <FieldsList
            v-else
            :node="$node"
            :base="source.queryPath"
            :max-nesting="source.queryField ? 0 : 1"
            :max-height="maxHeight"
            @resolve="resolve"
        />
    </div>
</template>

<script>
import essentials from 'yooessentials';
import Source from '../Source';
import FieldsList from './FieldsList.vue';
import QueryFieldsList from './QueryFieldsList.vue';

export default {
    name: 'DynamicDropdown',

    components: {
        FieldsList,
        QueryFieldsList
    },

    provide() {
        return {
            DynamicDropdown: this
        };
    },

    props: {
        node: {
            type: Object,
            required: true
        },
        field: {
            type: Object,
            required: true
        },
        omittedOptions: {
            type: Array,
            default: () => []
        },
        maxHeight: {
            type: String,
            default: 'large'
        }
    },

    data: (vm) => ({
        source: new Source({}, vm.node)
    }),

    computed: {
        $node() {
            return essentials.helpers.Source.resolveAdjacentNode(this.node);
        },
        options() {
            return essentials.helpers.Dynamic.matchOptions(this.$node).filter(
                (opt) => !this.omittedOptions.includes(opt.name)
            );
        }
    },

    methods: {
        setQuery([name, option]) {
            if (option?.directResolve) {
                const source = option.directResolve(this.$node, this.field.name);
                this.$emit('resolve', source);
                return;
            }

            this.source.query = name;
        },

        resolve(field) {
            this.source.field = field;
            this.$emit('resolve', this.source.values);
        }
    }
};
</script>
