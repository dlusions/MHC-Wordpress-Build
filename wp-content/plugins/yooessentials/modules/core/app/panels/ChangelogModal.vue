<script>
import api from '@yootheme/api';
import http from '@yootheme/http';
import { Asset } from '../utilities';
import essentials from 'yooessentials';

const labels = {
    'New Features': 'success',
    'Bug Fixes': 'danger',
    Changes: 'warning'
};

export default {
    data: () => ({
        changelog: ''
    }),
    async mounted() {
        const { base: yoobase } = api.customizer;
        const { base } = essentials.customizer;
        const { version } = essentials;

        await Asset.js(`${yoobase}/vendor/assets/marked/lib/marked.umd.js`);

        this.changelog = await parse(
            await http(`${base}/CHANGELOG.md`).query({ version }).get().text()
        );
    }
};

async function parse(markdown) {
    let section;
    const renderer = {
        list({ items }) {
            const list = `<ul class="uk-list uk-list-square">${items
                .map((item) => this.listitem(item))
                .join('')}</ul>`;

            if (section) {
                return `<h4 class="uk-success">
                        <span class="uk-label uk-label-${labels[section]}">${section}</span>
                    </h4>${list}`;
            }

            return list;
        },

        listitem(token) {
            return `<li>${this.text(token)}</li>`;
        },

        heading({ text, depth }) {
            text = text.replace(/\(.*?\)/, '<span class="uk-text-muted">$&</span>');

            if (depth === 2) {
                return `<h${depth}>${text}</h${depth}>`;
            }

            if (depth === 3) {
                section = text;
            }

            return '';
        }
    };

    return new window.marked.Marked({ renderer }).parse(markdown, {
        async: true,
        mangle: false,
        headerIds: false
    });
}
</script>

<template>
    <div>
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">{{ $t('Changelog') }}</h3>
        </div>

        <div class="uk-modal-body" uk-overflow-auto v-html="changelog"></div>
    </div>
</template>
