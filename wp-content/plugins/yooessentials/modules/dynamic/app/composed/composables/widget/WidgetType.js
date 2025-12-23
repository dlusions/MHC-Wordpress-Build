/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { WidgetType } from '@yooessentials/codemirror';
import { composedField } from '@yooessentials/dynamic/composed/state';

export default class Widget extends WidgetType {
    constructor({ id, type, component, ...props }) {
        super();

        this.id = id;
        this.type = type;
        this.props = props;
        this.component = component;
    }

    toDOM(view) {
        const { Editor } = view.state.field(composedField);

        this.view = view;

        this.vm = new Vue({
            parent: Editor,
            extends: this.component,
            propsData: {
                view,
                id: this.id,
                type: this.type,
                ...this.props
            }
        }).$mount();

        return this.vm.$el;
    }

    ignoreEvent(e) {
        if (e.type === 'dragstart') {
            e.dataTransfer.dropEffect = 'move';
            e.dataTransfer.effectAllowed = 'move';
            return false;
        }

        return true;
    }

    eq(widget) {
        return widget.id === this.id;
    }

    updateDom() {
        return true;
    }

    destroy() {
        this.vm?.$destroy();
        super.destroy();
    }
}
