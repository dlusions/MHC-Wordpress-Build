/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { deepCopy } from '@yooessentials/util';
import { EditorView, EditorState } from '@yooessentials/codemirror';
import { composedField } from '../state';
import { generateId, getSelectedText } from '../helper';

const temp = [];

const eventHandlers = EditorView.domEventObservers({
    copy(e, view) {
        const { types } = view.state.field(composedField);

        types.forEach((type) => {
            const composed = view.state.field(composedField);
            const storage = composed[type.storage];
            const selectedText = getSelectedText(view.state);

            const selected = [
                ...selectedText.matchAll(new RegExp(`{{ ${type.storage}\\.(\\w+) }}`, 'g'))
            ].map((match) => match[1]);

            selected.filter((id) => storage[id]).forEach((id) => storeTemp(id, type, storage[id]));
        });
    }
});

const handleInputs = EditorState.transactionExtender.of((tr) => {
    const { types } = tr.state.field(composedField);

    types.forEach((type) => {
        const doc = tr.state.doc.toString();
        const composed = tr.state.field(composedField);
        const storage = composed[type.storage];

        const widgetsIds = [...doc.matchAll(new RegExp(`{{ ${type.storage}\\.(\\w+) }}`, 'g'))].map(
            (match) => match[1]
        );

        // for each composable that a widget is missing
        Object.keys(storage)
            .filter((id) => !widgetsIds.some((wId) => wId === id))
            .forEach((id) => {
                // perhaps was removed by user, lets store it in temp
                storeTemp(id, type, storage[id]);

                // and remove from it storage
                Vue.delete(storage, id);
            });

        // restore temp if matching widgets reappear (eg on undo)
        widgetsIds.forEach((id) => {
            const item = findTemp(id, type);

            if (item) {
                restoreTemp(tr.state, item);
            }
        });
    });
});

const handleDuplication = EditorState.transactionFilter.of((tr) => {
    if (!tr.docChanged) {
        return tr;
    }

    const { types } = tr.state.field(composedField);

    const doc = tr.state.doc.toString();

    return [
        tr,
        ...types.flatMap((type) => {
            const widgetsIds = [
                ...doc.matchAll(new RegExp(`{{ ${type.storage}\\.(\\w+) }}`, 'g'))
            ].map((match) => match[1]);

            const duplicates = widgetsIds.filter((id, index, self) => self.indexOf(id) !== index);

            if (duplicates.length) {
                const composed = tr.state.field(composedField);
                const storage = composed[type.storage];

                return duplicates.map((id) => {
                    const matched = doc.match(`{{ ${type.storage}.${id} }}`);

                    const newId = generateId();
                    const newSource = deepCopy(storage[id]);

                    Vue.set(storage, newId, newSource);

                    return {
                        sequential: true,
                        changes: {
                            from: matched.index,
                            to: matched.index + matched[0].length,
                            insert: matched[0].replace(id, newId)
                        }
                    };
                });
            }
        })
    ].filter(Boolean);
});

export function storeTemp(id, type, value) {
    temp.push({ id, type: type.name, value: deepCopy(value) });
}

function restoreTemp(state, item, remove = true) {
    const composed = state.field(composedField);
    const type = composed.types.find((t) => t.name === item.type);
    const storage = composed[type.storage];

    Vue.set(storage, item.id, item.value);

    if (remove) {
        temp.splice(temp.indexOf(item), 1);
    }
}

function findTemp(id, type) {
    return temp.find((item) => item.id === id && item.type == type.name);
}

export default [eventHandlers, handleInputs, handleDuplication];
