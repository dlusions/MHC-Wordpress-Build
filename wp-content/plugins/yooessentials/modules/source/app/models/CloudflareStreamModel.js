/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

// import Vue from 'vue';
// import {isArray} from 'uikit-util';
// import essentials from 'yooessentials';

// import {get, set} from '@yooessentials/util';
import http from '@yootheme/http';

const baseUrl = 'yooessentials/cloudflare';
const $streams = {};

export default {
    name: 'StreamModel',

    // inject: ['Config'],

    data: () => ({
        streams: $streams
    }),

    watch: {
        // streams(value) {
        //     // value.forEach(stream => {
        //     //     cache[stream.uid] = stream;
        //     // });
        // }
    },

    methods: {
        // load(params) {
        //     return this.fetchStreams(params).then(({data}) => {
        //         this.streams = data || [];
        //     }).catch(() => {});
        // },

        // update(streams, data) {
        //     streams = isArray(streams) ? streams : [streams];
        //     return Promise.all(streams.map(({uid}) => this.doUpdate(uid, data)));
        // },

        // doUpdate(uid, data) {
        //     return http(`${baseUrl}/stream`).post({uid, data}).json(({data: {result}}) => {
        //         const index = this.streams.findIndex(({uid}) => uid === result.uid);
        //         this.$set(this.streams, index, {...this.streams[index], ...result});
        //     });
        // },

        // remove(streams) {
        //     streams = isArray(streams) ? streams : [streams];
        //     return Promise.all(streams.map(this.doRemove));
        // },

        // doRemove({uid}) {
        //     return http(`${baseUrl}/stream`).delete({
        //         params: {uid}
        //     });
        // },

        fetchStream(uid, params = {}) {
            if (this.streams[uid]) {
                return Promise.resolve(this.streams[uid]);
            }

            this.$trigger('yooessentials-prerequest', params);

            return new Promise((resolve, reject) => {
                http(`${baseUrl}/stream`)
                    .post({ uid, ...params })
                    .json(({ data: { success, errors, result } }) => {
                        success ? resolve(result) : reject(errors);
                    })
                    .catch((e) => reject(e?.json));
            }).then((stream) => {
                this.streams[stream.uid] = stream;
            });
        },

        fetchStreams(params = {}) {
            this.$trigger('yooessentials-prerequest', params);

            return http(`${baseUrl}/streams`)
                .post(params)
                .json((streams) => {
                    streams.forEach((stream) => {
                        this.streams[stream.uid] = stream;
                    });

                    return streams;
                });
        }
    }
};
