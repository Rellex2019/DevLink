import './bootstrap'
import { createApp } from 'vue';
import App from './App.vue';

import store from './store';

import router from './router';

import alertPlugin from './plugins/alertPlugin';



store.commit('authStore/initializeStore');

const app = createApp(App);

app.directive('focus', {
    mounted(el) {
        el.focus();
    }
});


app.use(store);
app.use(alertPlugin);
app.use(router);
app.mount('#app');