import './bootstrap'
import { createApp } from 'vue';
import App from './App.vue';

import { createRouter, createWebHistory } from 'vue-router';

import Main from './pages/Main.vue';
import Dashboard from './pages/Dashboard.vue';
import Repository from './pages/Repository.vue';

const router = createRouter({
    routes: [
    {
        path: '/', 
        component: Main
    },
    {
        path:'/repository',
        component: Repository
    }], 
    history: createWebHistory()
})

const app = createApp(App);
app.use(router);
app.mount('#app');