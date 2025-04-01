
import { createApp } from 'vue';
import AlertComponent from '@/js/components/others/AlertComponent.vue';

const alertPlugin = {
    install(app) {
        const alertComponent = createApp(AlertComponent);
        const instance = alertComponent.mount(document.createElement('div'));

        document.body.appendChild(instance.$el);

        app.config.globalProperties.$showAlert = (message, type = 'alert') => {
            instance.showAlert(message, type);
        };
    }
};

export default alertPlugin;
