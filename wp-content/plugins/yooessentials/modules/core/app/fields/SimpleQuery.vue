<template>
    <div v-if="!$connection">Failed to connect to conditions.</div>

    <input v-else-if="attributes.disabled" disabled class="uk-input" :value="input" />

    <div v-else>
        <input
            v-model.trim="input"
            :list="id"
            v-bind="attributes"
            class="uk-input"
            type="text"
            :placeholder="createQuery('AND')"
            :class="{
                'uk-text-danger': error
            }"
        />

        <div v-if="error" class="uk-text-danger uk-text-small">{{ error }}</div>

        <datalist :id="id">
            <option v-if="conditions.length === 2" :value="createQuery('AND')">AND</option>
            <option v-if="conditions.length === 2" :value="createQuery('OR')">OR</option>
            <option v-if="conditions.length > 2" :value="createGroupedQuery('AND')">AND</option>
            <option v-if="conditions.length > 2" :value="createGroupedQuery('OR')">OR</option>
        </datalist>
    </div>
</template>

<script>
import fields from '@yootheme/fields';

const errors = {
    'unknown-condition': 'Unknown condition stated',
    'missing-condition': 'Not all conditions stated',
    'invalid-syntax': 'Invalid Syntax'
};

export default {
    extends: fields.components.FieldText,

    data: () => ({
        draft: ''
    }),

    computed: {
        input: {
            set(val) {
                this.draft = val;

                if (!this.error) {
                    this.value = val;
                }
            },

            get() {
                return this.draft;
            }
        },

        validation() {
            return this.validate(this.draft);
        },

        error() {
            if (this.validation !== true) {
                return errors[this.validation];
            }

            return '';
        },

        id() {
            return `simple-query-field-${this.name}`;
        },

        $connection() {
            return this.values?.[this.field?.connection];
        },

        conditions() {
            return (this.$connection || []).map((c, i) => (i + 1).toString());
        },

        totalConditions() {
            return this.conditions.length;
        },

        defaultQuery() {
            return this.conditions.map((q) => `(${q})`).join(' AND ');
        }
    },

    created() {
        this.draft = this.value;
    },

    methods: {
        createQuery(operator = 'AND') {
            return this.conditions.map((q) => `{${q}}`).join(` ${operator} `);
        },

        createGroupedQuery(operator = 'AND') {
            const inverse = operator === 'AND' ? 'OR' : 'AND';

            const query = this.createQuery(inverse).replace(/(\{\d\} (OR|AND) \{\d\})/g, '($1)');

            if (operator === 'AND') {
                return query.replace(/\) (OR) (\{|\()/g, ') AND $2');
            } else {
                return query.replace(/\) (AND) (\{|\()/g, ') OR $2');
            }
        },

        validate(value) {
            if (!value) {
                return true;
            }

            // Only allow digits, curly braces, spaces, AND, OR, and parentheses
            if (/[^0-9{}()ANDOR \s]/.test(value)) {
                return 'invalid-syntax';
            }

            // No double spaces
            if (/ {2,}/.test(value)) {
                return 'invalid-syntax';
            }

            // No empty parenthesis
            if (value.match(/\(\s*\)/)) {
                return 'invalid-syntax';
            }

            // Parenthesis must be balanced
            const openPar = (value.match(/\(/g) || []).length;
            const closePar = (value.match(/\)/g) || []).length;
            if (openPar !== closePar) {
                return 'invalid-syntax';
            }

            // Braces must be balanced
            const openBrace = (value.match(/\{/g) || []).length;
            const closeBrace = (value.match(/\}/g) || []).length;
            if (openBrace !== closeBrace) {
                return 'invalid-syntax';
            }

            // Tokenize: match valid tokens and ignore whitespace
            const tokens = value.match(/\(|\)|\{\d+\}|AND|OR/g);
            if (!tokens) {
                return 'invalid-syntax';
            }

            // Remove all whitespace and check if the joined tokens match the input (ignoring whitespace)
            const compactInput = value.replace(/\s+/g, '');
            const compactTokens = tokens.join('');
            if (compactTokens !== compactInput) {
                return 'invalid-syntax';
            }

            // Validate each token
            for (let token of tokens) {
                if (token.match(/^\{\d+\}$/)) {
                    // Only allow {n} where n is a valid condition
                    const n = token.slice(1, -1);
                    if (!this.conditions.includes(n)) {
                        return 'unknown-condition';
                    }
                } else if (!['AND', 'OR', '(', ')'].includes(token)) {
                    return 'invalid-syntax';
                }
            }

            // No operators at the start or end
            if (
                ['AND', 'OR'].includes(tokens[0]) ||
                ['AND', 'OR'].includes(tokens[tokens.length - 1])
            ) {
                return 'invalid-syntax';
            }

            // No consecutive operators or consecutive conditions without operator
            for (let i = 1; i < tokens.length; i++) {
                const prev = tokens[i - 1];
                const curr = tokens[i];
                if (
                    (['AND', 'OR'].includes(prev) && ['AND', 'OR'].includes(curr)) ||
                    (prev.match(/^\{\d+\}$/) && curr.match(/^\{\d+\}$/))
                ) {
                    return 'invalid-syntax';
                }
            }

            // No operator just before closing parenthesis or just after opening parenthesis
            for (let i = 1; i < tokens.length; i++) {
                if (
                    (tokens[i - 1] === '(' && ['AND', 'OR'].includes(tokens[i])) ||
                    (['AND', 'OR'].includes(tokens[i - 1]) && tokens[i] === ')')
                ) {
                    return 'invalid-syntax';
                }
            }

            return true;
        }
    }
};
</script>
