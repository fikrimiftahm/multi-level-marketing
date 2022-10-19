require("./bootstrap");

import Vue from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue";
import { InertiaProgress } from "@inertiajs/progress";
import vuetify from "./Plugins/vuetify";
import axios from "axios";
import moment from "moment";

Vue.prototype.$axios = axios;
Vue.prototype.moment = moment;

InertiaProgress.init({
    color: "#1f1d55",
});

createInertiaApp({
    resolve: (name) =>
        import (`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        Vue.use(plugin);

        new Vue({
            vuetify,
            render: (h) => h(App, props),
        }).$mount(el);
    },
});