<template>
    <div class="container-alert-block">
        <transition-group name="fade-alert" tag="div">
            <!-- Комнонент принимает два значения 1. message 2. type - тип уведомления -->
            <!-- 'accept', 'error', 'alert' если не писать 2 аргумент, то по умолчанию 'alert' -->
            <div v-for="(alert, index) in alerts" :key="index" :class="alert.type=='accept' ? 'accept' : alert.type=='error'?'error':'alert'"
                class="alert-block">{{ alert.message }}
            </div>
        </transition-group>
    </div>
</template>
<!-- <AlertComponent ref="alertComponent"/> -->
<script>
export default {
    name: 'AlertComponent',
    data() {
        return {
            alerts: []
        }
    },
    props: {
        type: {
            type: String,
            required: false,
        }
    },
    methods: {
        //Может быть несльколько сообщений одновременно, поэтому используем массив
        showAlert(message, type) {
            this.alerts.push({ message, type });
            setTimeout(() => {
                this.alerts.shift();
            }, 3000)
        }
    }
}
</script>

<style scoped>
.alert-block {
    position: fixed;
    right: 50px;
    bottom: 50px;
    display: flex;
    font-family: 'Roboto';
    justify-content: center;
    align-items: center;
    min-height: 50px;
    padding: 5px 15px;
    border-radius: 10px;
}

.error {
    background-color: #FFB3B3;
}

.accept {
    background-color: #B3FFB3;
}
.alert{
    background-color: #5498e0;
}

.fade-alert-enter-from, .fade-alert-leave-to {
    transform: translateX(100px);
    opacity: 0;
}
.fade-alert-enter-active, .fade-alert-leave-active {
    transition: all 0.3s ease-in-out;
}
.fade-alert-leave {
    transform: translateX(0);
    opacity: 1;
}
</style>