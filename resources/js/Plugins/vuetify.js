import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

Vue.use(Vuetify)

const opts = {
    theme: {
        dark: false,
        themes: {
            light: {
                primary: '#1f1d55',
                secondary: '#FFCBO5',
                accent: '#32BCAD',
                error: '#B71C1C',
                background: '#1f1d55'
            },
            dark: {
                primary: '#1f1d55',
                secondary: '#FFCBO5',
                accent: '#32BCAD',
                error: '#B71C1C',
                background: '#1f1d55'
            },
        },
        options: {
            customProperties: true
        },
    },
    icons: {
        iconfont: 'mdi',
    },
}

export default new Vuetify(opts)