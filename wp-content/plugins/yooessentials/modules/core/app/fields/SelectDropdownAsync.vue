<script>
import api from '@yootheme/api';
import http from '@yootheme/http';
import { debounce, keyBy } from '../util';
import { DropdownList } from '../components';
import SelectDropdownField from './SelectDropdown.vue';
import { isArray, isString } from 'uikit-util';

export default {
    name: 'SelectDropdownAsync',

    extends: SelectDropdownField,

    inject: ['Fields'],

    data: () => ({
        fetching: false,
        lastFetched: null
    }),

    computed: {
        watching() {
            return this.field.watch?.split(',') || [];
        },
        selectedText() {
            const entries = keyBy(this.entries, 'value');
            const text = entries?.[this.selected]?.text || this.metadata.name || this.selected;

            if (this.fetching) {
                return `${text} ...`;
            }

            return text;
        },
        metadata: {
            get() {
                return this.values._meta?.fields?.[this.field.name] || {};
            },

            set(val) {
                // if (this.field.meta !== true) {
                //     return;
                // }
                // const meta = this.values._meta || {};
                // set(meta, `fields.${this.field.name}`, val);
                // // this.$set(this.values, '_meta', meta);
            }
        }
    },

    watch: {
        selected() {
            if (this.selectedText) {
                this.metadata = { name: this.selectedText };
            }
        },

        entries() {
            if (this.selectedText) {
                this.metadata = { name: this.selectedText };
            }
        }
    },

    mounted() {
        if (this.attributes?.disabled) {
            this.$watch('attributes.disabled', (disabled) => {
                disabled || this.fetch();
            });
        }

        this.watching.forEach((watch) => {
            this.$watch(
                `values.${watch}`,
                debounce(() => {
                    this.fetch({}, true);
                }, 100)
            );
        });

        if (!this.selected && this.field.autoSelect && !this.watch) {
            this.fetch().then(() => {
                if (this.entries.length === 1) {
                    this.selected = this.entries[0].value;
                }
            });
        }
    },

    methods: {
        async open() {
            if (this.fetching) {
                return;
            }

            this.watching.forEach((watch) => {
                const watchedValue = this.values[watch];

                if (!watchedValue) {
                    const fieldslit = this.flattenFields(this.Fields.fieldset);
                    const label = fieldslit[watch]?.label;

                    this.error = `${label} must be specified`;
                    return;
                }
            });

            await this.fetch();

            if (this.error) {
                return;
            }

            const value = await api.uikit.promptDropdown(
                {
                    functional: true,
                    render: (h, { listeners }) =>
                        h(DropdownList, {
                            props: {
                                async: true,
                                entries: this.entries,
                                selected: this.selected,
                                fetching: this.fetching,
                                maxHeight: this.field.dropsize
                            },
                            on: {
                                search: (val) => {
                                    this.fetch({ query: val }, true);
                                },
                                resolve: (item) => {
                                    listeners.resolve(item);
                                }
                            }
                        })
                },
                {},
                this.$el,
                { classes: 'yo-dropdown', flip: false }
            );

            if (value) {
                this.selected = value;
            }
        },

        fetch(params = {}, force = false) {
            const msSinceLastFetch = Date.now() - this.lastFetched;

            if (!force && msSinceLastFetch < 20000) {
                return;
            }

            params = { ...params, ...(this.field.params || {}) };
            this.$trigger('yooessentials-resolve-field-argument', params);

            this.error = false;
            this.fetching = true;

            return http(this.field.endpoint)
                .post(params)
                .json()
                .then((options) => {
                    if (isArray(options)) {
                        this.entries = this.flattenOptions(options);
                        this.lastFetched = Date.now();
                    } else {
                        this.error = isString(options) ? options : 'Invalid Options';
                    }

                    this.fetching = false;
                    return options;
                })
                .catch((e) => {
                    if (e instanceof SyntaxError) {
                        this.error = e;
                    } else if (e.status === 404) {
                        this.error = 'Endpoint not found: ' + e?.response?.url;
                    } else if (e.status === 400) {
                        this.error = e.text;
                    }

                    this.fetching = false;
                });
        },

        flattenOptions(options = [], group = '') {
            return options.reduce((carry, option) => {
                return [
                    ...carry,
                    ...(option.group
                        ? this.flattenOptions(option.group, option.text)
                        : [{ ...option, group }])
                ];
            }, []);
        },

        flattenFields(obj, result = {}) {
            const fields = obj.fields;

            for (const key in fields) {
                const field = fields[key];

                if (field.fields) {
                    this.flattenFields(field, result);
                } else {
                    result[key] = field;
                }
            }

            return result;
        }
    }
};
</script>
