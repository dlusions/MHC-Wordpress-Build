/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export default ({
    modal,
    util: {
        $,
        $$,
        trigger,
        removeAttr,
        addClass,
        removeClass,
        empty,
        html,
        each,
        on,
        includes,
        scrollIntoView
    }
}) => ({
    connected() {
        this.$submit = $$('button[type="submit"]', this.$el);
        this.$errors = $('[data-yooessentials-form-errors]', this.$el);
        this.$fields = $$('[data-yooessentials-form-field]', this.$el).reduce(
            (carry, el) => ({ ...carry, [el.dataset.yooessentialsFormField]: el }),
            {}
        );

        // enable submit btn
        removeAttr(this.$submit, 'disabled');
    },

    props: {
        errors: Array,
        action: String,
        config: Object,
        validateAction: String,
        validationErrors: Array
    },

    data: () => ({
        action: '',
        errors: [],
        config: {},
        submitting: false,
        validateAction: '',
        validationErrors: []
    }),

    events: {
        submit(e) {
            e.preventDefault();

            if (this.config.html5validation && !this.$el.reportValidity()) {
                const data = new FormData(this.$el);

                trigger(this.$el, 'form:validation-error', [{ data }]);
                trigger(document, 'yooessentials-form:validation-error', [
                    { form: this.$el, data }
                ]);
                return;
            }

            this.submit();
        },

        change(e) {
            const field = e.target;
            const data = new FormData(this.$el);

            trigger(this.$el, 'form:field-change', [{ data }]);
            trigger(document, 'yooessentials-form:field-change', [{ form: this.$el, field, data }]);
        }
    },

    methods: {
        submit() {
            const data = new FormData(this.$el);
            let prevented = false;

            prevented = !trigger(this.$el, 'form:submit', [{ data }]);
            prevented =
                prevented ||
                !trigger(document, 'yooessentials-form:submit', [{ form: this.$el, data }]);

            if (prevented) {
                return;
            }

            this.doSubmit();
        },

        enableSpinner() {
            each(this.$submit, (btn) => {
                const content = $('.ye-form--btn-content', btn);
                const spinner = $('.ye-form--btn-spinner', btn);

                addClass(content, 'uk-invisible');
                removeClass(spinner, 'uk-hidden');
            });
        },

        disableSpinner() {
            each(this.$submit, (btn) => {
                const content = $('.ye-form--btn-content', btn);
                const spinner = $('.ye-form--btn-spinner', btn);

                removeClass(content, 'uk-invisible');
                addClass(spinner, 'uk-hidden');
            });
        },

        doSubmit(moreData) {
            this.submitting = true;

            // set spinner with delay
            setTimeout(() => {
                if (this.submitting === true) {
                    this.enableSpinner();
                }
            }, 200);

            const data = new FormData(this.$el);

            for (const key in moreData) {
                data.append(key, moreData[key]);
            }

            fetch(this.validateAction || this.action, {
                body: data,
                method: 'POST',
                responseType: 'json',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then((res) => res.json())
                .then((response) => {
                    if (!response.success) {
                        this.processErrors({ response });
                        return;
                    }

                    trigger(this.$el, 'form:submitted', [{ response, data }]);
                    trigger(document, 'yooessentials-form:submitted', [
                        { form: this.$el, response, data }
                    ]);

                    if (this.validateAction) {
                        this.$el.submit();
                    }

                    this.resetForm();
                })
                .catch((error) => {
                    const status = error?.xhr?.status || 500;
                    let statusText = error?.xhr?.statusText || 'Internal Server Error';

                    if (status === 301 || status === 404) {
                        statusText = 'Submission endpoint not found.';
                    }

                    this.processErrors({
                        data,
                        status,
                        statusText,
                        response: error?.xhr?.response
                    });
                });
        },

        processErrors({ response, statusText, data }) {
            this.submitting = false;
            this.disableSpinner();
            this.resetErrorsDisplay();
            this.resetValidationErrorsDisplay();

            const validation = (this.validationErrors = response?.validation ?? []);

            this.errors = response?.errors ?? [
                'Submission failed, please try again or contact us about this issue.',
                statusText
            ];

            if (validation['']?.length) {
                this.errors = [...this.errors, ...validation['']];
            }

            this.displayErrors();

            const payload = { errors: this.errors, validation, data };

            trigger(this.$el, 'form:submission-error', [payload]);
            trigger(document, 'yooessentials-form:submission-error', [
                { form: this.$el, ...payload }
            ]);
        },

        displayErrors() {
            this.displayValidationErrors();
            this.displaySubmissionErrors();

            if (this.config.errors_display?.modal) {
                this.displayAllErrorsInModal();
                return;
            }
        },

        displayAllErrorsInModal() {
            let errors = [...this.errors];

            for (const name in this.validationErrors) {
                errors = [...errors, ...this.validationErrors[name]];
            }

            let content = this.config.errors_display?.modal_content || '';
            const errorsList = `<ul class="uk-list">${errors
                .map((e) => `<li>${e}</li>`)
                .join('')}</ul>`;

            // enforce presence of errors placeholder
            if (!includes(content, '{errors}')) {
                content = '{errors}';
            }

            content = content.replace('{errors}', errorsList);

            const modalInstance = modal.alert(content).dialog;

            on(modalInstance.$el, 'beforeshow', () => {
                if (this.config.errors_display?.modal_center) {
                    addClass(modalInstance.panel, 'uk-margin-auto-vertical');
                }
            });
        },

        displaySubmissionErrors() {
            this.resetErrorsDisplay();

            if (!this.errors.length) {
                return;
            }

            const list = this.errors.map((e) => `<li>${e}</li>`).join('');

            html(
                this.$errors,
                `<div class="uk-alert-warning uk-margin-top" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <ul class="uk-list">${list}</ul>
                </div>`
            );
        },

        displayValidationErrors() {
            this.resetValidationErrorsDisplay();
            const errors = this.validationErrors;

            let scrollTo;

            for (const name in errors) {
                const field = this.$fields[name];
                const fieldErrors = errors[name];

                scrollTo = scrollTo || field;

                if (!field) {
                    this.errors.push(errors[name]);
                    continue;
                }

                const $controls = $$('[name]', field);
                const $errors = $$('[data-yooessentials-form-field-errors]', field);

                addClass($controls, 'uk-form-danger');
                html(
                    $errors,
                    '<ul class="uk-list">' + fieldErrors.map((e) => `<li>${e}</li>`) + '</ul>'
                );
            }

            if (scrollTo) {
                scrollIntoView(scrollTo);
            }
        },

        resetForm() {
            this.errors = [];
            this.validationErrors = [];
            this.submitting = false;

            if (this.config.reset_after_submit) {
                this.$el.reset();
            }

            this.disableSpinner();
            this.resetErrorsDisplay();
            this.resetValidationErrorsDisplay();
        },

        resetErrorsDisplay() {
            empty(this.$errors);
        },

        resetValidationErrorsDisplay() {
            for (const name in this.$fields) {
                const field = this.$fields[name];
                const $controls = $$('[name]', field);
                const $errors = $$('[data-yooessentials-form-field-errors]', field);

                removeClass($controls, 'uk-form-danger');
                empty($errors);
            }
        }
    }
});
