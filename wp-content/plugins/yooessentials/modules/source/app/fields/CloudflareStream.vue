<template>
    <div>
        <a
            v-if="!value"
            href
            :class="{ 'uk-disabled uk-text-muted': attributes.disabled }"
            class="uk-placeholder uk-text-center uk-display-block uk-margin-remove"
            @click.prevent="open"
        >
            <svg
                width="30"
                height="30"
                viewBox="0 0 30 30"
                xmlns="http://www.w3.org/2000/svg"
                class="uk-svg"
            >
                <polygon
                    fill="none"
                    stroke="#444"
                    stroke-width="2"
                    points="12,10.5 20,15.1 12,19.5"
                />
                <rect
                    x="1"
                    y="4"
                    fill="none"
                    stroke="#444"
                    stroke-width="2"
                    width="28"
                    height="22"
                />
            </svg>
            <p class="uk-h6 uk-margin-small-top">{{ $t('Select Video') }}</p>
        </a>

        <div
            v-else-if="stream"
            class="uk-position-relative uk-transition-toggle uk-text-center yo-thumbnail"
            tabindex="0"
        >
            <img v-if="!stream.requireSignedURLs" :src="$url({ url: stream.thumbnail })" />

            <template v-else>
                <span class="uk-placeholder uk-text-center uk-display-block uk-margin-remove">
                    <svg
                        width="30"
                        height="30"
                        viewBox="0 0 30 30"
                        xmlns="http://www.w3.org/2000/svg"
                        class="uk-svg"
                    >
                        <polygon
                            fill="none"
                            stroke="#444"
                            stroke-width="2"
                            points="12,10.5 20,15.1 12,19.5"
                        />
                        <rect
                            x="1"
                            y="4"
                            fill="none"
                            stroke="#444"
                            stroke-width="2"
                            width="28"
                            height="22"
                        />
                    </svg>
                    <p class="uk-h6 uk-margin-small-top">{{ $t('Private Video') }}</p>
                </span>
            </template>

            <div class="uk-transition-fade uk-position-cover yo-thumbnail-overlay"></div>

            <a href class="uk-position-cover" @click.prevent="open"></a>

            <div class="uk-transition-fade uk-position-top-right yo-thumbnail-badge uk-light">
                <a
                    href
                    :title="$t('Delete')"
                    class="uk-icon-link"
                    uk-icon="trash"
                    uk-tooltip="delay: 500"
                    @click.prevent="value = ''"
                ></a>
            </div>
        </div>

        <div class="uk-margin-small-top">
            <!-- <input v-model="value" type="text" class="uk-input yo-input-iconnav-right" v-bind="attributes"> -->

            <a href :class="[attributes.class, 'uk-input']" @click.prevent="open()">
                <template v-if="stream">
                    {{ stream | name }}
                </template>

                <template v-else>
                    {{ value }}
                </template>
            </a>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import { CloudflareStreamPicker } from '../components';
import { CloudflareStreamModel } from '../models';
import { storage } from '@yooessentials/util';

const storageKey = 'yooessentials.cloudflare-stream.field';

export default {
    name: 'CloudflareStream',

    filters: {
        name(stream) {
            return stream?.meta?.name || stream.uid;
        }
    },

    extends: fields.components.FieldText,

    models: {
        Stream: CloudflareStreamModel
    },

    computed: {
        stream() {
            try {
                return (
                    (this.value && this.Stream.streams[this.value]) ||
                    JSON.parse(storage[storageKey])
                );
            } catch (e) {
                console.error(e);
            }

            return null;
        }
    },

    methods: {
        async open() {
            const stream = await api.uikit.promptModal(
                CloudflareStreamPicker,
                {
                    sourceId: this.field.sourceId
                },
                { container: true }
            );

            if (stream) {
                this.value = stream.uid;
                storage[storageKey] = JSON.stringify(stream);
            }
        }
    }
};
</script>
