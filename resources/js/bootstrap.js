import _ from 'lodash';

// Импортируем библиотеку axios
import axios from 'axios';
import Cookies from 'js-cookie';

axios.interceptors.request.use(config => {
    let token = Cookies.get('XSRF-TOKEN') || document.querySelector('meta[name="csrf-token"]')?.content;
    // Декодируем токен если он URL-encoded
    if (token && token.includes('%3D')) {
        token = decodeURIComponent(token);
        console.log('Decoded token:', token);
    }
    if (token) {
        config.headers['X-XSRF-TOKEN'] = token;
    } else {
        console.error('CSRF token not found');
    }
    return config;
});
window._ = _; // Присваиваем Lodash глобальной переменной

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;


window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';




// Настройка Laravel Echo (по желанию)
// import Echo from 'laravel-echo';
// window.Pusher = require('pusher-js');
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
